Appointments = new Mongo.Collection('appointments');

Appointments.allow({
    insert: function(userId, doc) {
        return !!userId;
    },
    update: function(userId, doc) {
        return !!userId;
    }
});

AppointmentsSchema = new SimpleSchema({
    date: {
        type: String,
        label: "Datum (dd-mm-jjjj)",
        optional: true
    },
    time: {
        type: String,
        label: "Tijdstip (uu:mm)",
        optional: true
    },
    datetime: {
        type: Date,
        optional: true,
        autoform: {
            type: "hidden"
        }
    },
    appointmentTypeId: {
        type: String,
        label: "Soort afspraak",
          autoform: {
            type: "select",
            firstOption: false,
            options: function () {
                var managerId = FlowRouter.getParam('managerId');
                return AppointmentTypes.find({userId: managerId}).map(function (doc) {
                    return { label: doc.name, value: doc._id };
                });
            }
        }
    },
    firstName: {
        type: String,
        label: "Voornaam"
    },
    lastName: {
        type: String,
        label: "Achternaam"
    },
    email: {
        type: String,
        label: "E-mail"
    },
    telephone: {
        type: String,
        label: "Telefoon"
    },
    remarks: {
        type: String,
        label: "Opmerking",
        optional: true,
        autoform: {
            afFieldInput: {
              type: "textarea"
            }
        }
    },
    status: {
        type: String,
        optional: true,
        defaultValue: "pending",
        autoform: {
            type: "hidden"
        }
    },
    appointmentTypeName: {
        type: String,
        optional: true,
        autoform: {
            type: "hidden"
        }
    },
    appointmentTypeDuration: {
        type: String,
        optional: true,
        autoform: {
            type: "hidden"
        }
    },
    creditId: {
        type: String,
        optional: true,
        autoform: {
            type: "hidden"
        }
    },
    userId: {
        type: String,
        optional: true,
        autoValue: function() {
            return this.userId;
        },
        autoform: {
            type: "hidden"
        }
    },
    createdAt: {
        type: Date,
        defaultValue: new Date(),
        autoform: {
            type: "hidden"
        }
    },
    updatedAt: {
        type: Date,
        autoValue: function() {
            return new Date();
        },
        autoform: {
            type: "hidden"
        }
    }
});

Appointments.attachSchema(AppointmentsSchema);

Meteor.methods({
    insertAppointment: function(doc) {
        AppointmentsSchema.clean(doc);

        var appointmentType = AppointmentTypes.findOne({_id: doc.appointmentTypeId}, {name: 1, duration: 1});
        var datetime        = moment(doc.date+' '+doc.time, 'DD-MM-YYYY HH:mm').toDate();
        var appointmentDay  = moment(doc.date).format('d');

        Appointments.insert({
            datetime: datetime,
            appointmentTypeId: doc.appointmentTypeId,
            appointmentTypeName: appointmentType.name,
            appointmentTypeDuration: appointmentType.duration,
            firstName: doc.firstName,
            lastName:  doc.lastName,
            email:     doc.email,
            telephone: doc.telephone,
            remarks:   doc.remarks,
            status:    doc.status,
            userId:    doc.userId,
            createdAt: doc.createdAt,
            updatedAt: doc.updatedAt
        });
    },
    updateAppointment: function(id, dateInput, timeInput) {
        var datetime = moment(dateInput+' '+timeInput, 'DD-MM-YYYY HH:mm').toDate();

        Appointments.update({_id: id}, {
            $set: {
                datetime: datetime
            }
        });
    },
    approveAppointment: function(id) {
        // Check if credit amount is sufficient
        var credits = Credits.find({userId: Meteor.userId(), appointmentId: null}, {sort: {createdAt: 1}});

        // Get creditID and link it to the appointment
        if(credits.count() > 0) {
            var credit = credits.fetch()[0];
            Credits.update(credit._id, {
                $set: {
                    appointmentId: id
                }
            });

            // Set appointment status to "approved"
            Appointments.update(id, {
                $set: {
                    status: "approved",
                    creditId: credit._id
                }
            });
        }
    },
    declineAppointment: function(id) {
        var appointment = Appointments.findOne({_id: id});
        
        if(appointment.status == 'approved') {
            //If appoinment was approved, check whether appointment is cancelled on time
            var now      = new Date();
            var deadline = moment(appointment.datetime).subtract(1, 'day');

            if(moment(now).isBefore(deadline)) {
                // If appointment is cancelled on time, return the linked credit and set status to cancelled.
                Credits.update(appointment.creditId, {
                    $set: {
                        appointmentId: null
                    }
                });
                Appointments.update(id, {
                    $set: {
                        status: "cancelled",
                        creditId: null
                    }
                });
            } else {
                // If appointment is not cancelled on time, do NOT return the linked credit and set status to no show.
                Appointments.update(id, {
                    $set: {
                        status: "noShow"
                    }
                });
            }
        } else {
            // If apppointment was pending, simply set status to declined. No credit was linked.
            Appointments.update(id, {
                $set: {
                    status: "declined"
                }
            });
        }
    },
    undoAppointment: function(id) {
        var appointment = Appointments.findOne({_id: id});

        Credits.update(appointment.creditId, {
            $set: {
                appointmentId: null
            }
        });
        Appointments.update(id, {
            $set: {
                status: "pending"
            }
        });
    }
});
