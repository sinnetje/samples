// SUBSCRIPTIONS
Template.WeekView.onCreated(function () {
    var self = this;
    self.autorun(function() {
        self.subscribe('workingHours');
        self.subscribe('appointmentTypes');
        self.subscribe('appointments');
        self.subscribe('credits');
        self.subscribe('holidays');

    });
});

var halfHourHeight = 21;
var hourHeight     = 2 * halfHourHeight;



// REACTIVE VARIABLES
Template.WeekViewAppointment.onCreated(function() {
    this.editMode = new ReactiveVar(false);
});


// EVENTS
Template.WeekView.events({
    'mousedown .week-view-appointment': function(event, template)
    {
        $('.week-view-appointment-detail',$(event.target).parents('.appointment-parent')).toggle();
    },
    'mousedown .close-appointment-button': function(event, template)
    {
        $('.week-view-appointment-detail',$(event.target).parents('.appointment-parent')).toggle();
    }
});

Template.WeekViewAppointment.events({
    'click .edit': function(event, template) {
        template.editMode.set(!template.editMode.get());
    },
    'click .decline': function() {
        Meteor.call('declineAppointment', this._id);
    },
    'click .cancel': function(event, template) {
        template.editMode.set(!template.editMode.get());
    },
    'click .approve': function() {
        Meteor.call('approveAppointment', this._id);
        Meteor.call('sendAppoinmentConfirmation', this._id);
        $('.week-view-appointment-detail',$(event.target).parents('.appointment-parent')).toggle();
    },
    'submit .edit-appointment-form-popup': function(event, template){
        event.preventDefault();

        var date = event.target.date.value;
        var time = event.target.time.value;

        Meteor.call('updateAppointment', this._id, date, time);
        $('.week-view-appointment-detail',$(event.target).parents('.appointment-parent')).toggle();
        template.editMode.set(!template.editMode.get());
        return false;
    }
});


var e =0;
// HELPERS
Template.WeekView.helpers({
    monthName: function() {
        return ucFirst(getSelectedDate().format('MMMM'));
    },
    weekNumber: function() {
        return getSelectedDate().format('w');
    },
    dateNumber: function() {
        return this.format('ddd DD/MM');
    },
    daysOfWeek: function() {
        var selectedDate = getSelectedDate();

        //Select first day of the first week
        var current    = moment(selectedDate).startOf('week');
        var end        = moment(selectedDate).endOf('week');
        var daysOfWeek = Array();

        while(current.isBefore(end)) {
            daysOfWeek.push(current.clone());
            current = current.add(1, 'days');
        }
        return daysOfWeek;
    },
    hoursOfDay: function() {
        // Get earliest opening time and latest closing hour to determine the hours that each workday should display
        var earliestOpening = WorkingHours.findOne({userId: Meteor.userId(), openingTime: {$ne:null}}, {sort: {openingTime: 1}}, {limit: 1});
        var latestClosing   = WorkingHours.findOne({userId: Meteor.userId(), closingTime: {$ne:null}}, {sort: {closingTime: -1}}, {limit: 1});

        // If working hours aren't set yet, set it by standard
        if(earliestOpening === undefined) {
            earliestOpeningTime = '00:00';
        } else {
            earliestOpeningTime = earliestOpening.openingTime;
        }

        if(latestClosing === undefined) {
            latestClosingTime = '24:00';
        } else {
            latestClosingTime = latestClosing.closingTime;
        }

        var current    = moment(earliestOpeningTime, 'HH:mm');
        var end        = moment(latestClosingTime, 'HH:mm');
        var hoursOfDay = Array();

        while(current.isBefore(end)) {
            hoursOfDay.push(current.clone());
            current = current.add(1, 'hours');
        }
        return hoursOfDay;
    },
    timeOfDay: function() {
        return this.format('HH:mm');
    },
    appointments: function() {
        var startTime = this.clone().startOf('day').toDate();
        var endTime   = this.clone().endOf('day').toDate();

        return Appointments.find({datetime: {'$gte': startTime, '$lt': endTime}, $or: [{status: 'approved'}, {status: 'cancelled'}, {status: 'pending'}]}, {sort: {datetime: 1}}).fetch();
    },
    holidays: function() {
        var startTime = this.clone().startOf('day').toDate();
        var endTime   = this.clone().endOf('day').toDate();

        return Holidays.find({startDatetime: {'$lte': startTime}, endDatetime: {'$gte': endTime}}).fetch();
    },
    currWeek: function() {
        return moment().format('Y/w');
    },
    prevWeek: function() {
        return getSelectedDate().clone().subtract(1, 'weeks').format('Y/w');
    },
    nextWeek: function() {
        return getSelectedDate().clone().add(1, 'weeks').format('Y/w');
    },
    isToday: function() {
        if(this.isSame(moment(), 'day')) {
            return true;
        }
        return false;
    },
    currentTimePosition: function() {
        var workDayDuration = null;

        // Calculate opening time offset
        var earliestOpening = WorkingHours.findOne({userId: Meteor.userId(), openingTime: {$ne:null}}, {sort: {openingTime: 1}}, {limit: 1});

        if(earliestOpening) {
            var openingTime = moment(earliestOpening.openingTime, 'HH:mm');
            var midnight    = moment().startOf('day');
            workDayDuration = openingTime.diff(midnight, 'hours');

            if(moment().isBefore(openingTime)) {
                return 0;
            } else {
                return timeDecimal * hourHeight - (hourHeight * workDayDuration);
            }
        }

        var time        = moment().format('HH:mm');
        var timeDecimal = moment.duration(time).asHours();

        return timeDecimal * hourHeight;
    }
});



Template.WeekViewAppointment.helpers({
    startTime: function() {
        return moment(this.datetime).format('HH:mm');
    },
    endTime: function() {
        return moment(this.datetime).add(this.appointmentTypeDuration, 'minutes').format('HH:mm');
    },
    oldAppointmentStyle: function() {
        var endTime = moment(this.datetime).add(this.appointmentTypeDuration, 'minutes');
        if(moment().isAfter(endTime, 'minute')) {
            return 'old-appointment';
        }
    },
    cancelledAppointmentStyle: function() {
        if(this.status == 'cancelled') {
            return 'cancelled-appointment';
        }
    },
    pendingAppointmentStyle: function() {
        if(this.status == 'pending') {
            return 'pending-appointment';
        }
    },
    appointmentPosition: function() {
        var workDayDuration = null;

        // Calculate opening time offset
        var earliestOpening = WorkingHours.findOne({userId: Meteor.userId(), openingTime: {$ne:null}}, {sort: {openingTime: 1}}, {limit: 1});

        if(earliestOpening) {
            var openingTime = moment(earliestOpening.openingTime, 'HH:mm');
            var midnight    = moment().startOf('day');
            workDayDuration = openingTime.diff(midnight, 'hours');
        }

        var time        = moment(this.datetime).format('HH:mm');
        var timeDecimal = moment.duration(time).asHours();

        if(workDayDuration) {
            return timeDecimal * hourHeight - (hourHeight * workDayDuration);
        }
        return timeDecimal * hourHeight;
    },
    appointmentHeight: function() {
        var height = halfHourHeight;
        var pixels = 0.7 * this.appointmentTypeDuration;

        if(pixels > halfHourHeight) {
            height = pixels;
        }
        return height;
    },
    appointmentStyle: function() {
        var duration = 0;

        if(this.appointmentTypeDuration < 42) {
            return 'appointment-short';
        } else {
            return 'appointment-long';
        }
    },
    appointmentDetailPosition: function() {
        var workDayDuration = null;

        // Calculate opening time offset
        var earliestOpening = WorkingHours.findOne({userId: Meteor.userId(), openingTime: {$ne:null}}, {sort: {openingTime: 1}}, {limit: 1});

        if(earliestOpening) {
            var openingTime = moment(earliestOpening.openingTime, 'HH:mm');
            var midnight    = moment().startOf('day');
            workDayDuration = openingTime.diff(midnight, 'hours');
        }

        var time        = moment(this.datetime).format('HH:mm');
        var timeDecimal = moment.duration(time).asHours();
        
        if(workDayDuration) {
            return timeDecimal * hourHeight - (hourHeight * workDayDuration);
        }
        return timeDecimal * hourHeight - 115;
    },
    editMode: function() {
        return Template.instance().editMode.get();
    },
    date: function() {
        return moment(this.datetime).format('DD-MM-YYYY');
    },
    isPending: function() {
        return this.status == 'pending' ? true : false;
    },
    hasEnoughCredits: function() {
        var creditsAvailableCount = Credits.find({userId: Meteor.userId(), appointmentId: null}).count();
        return (creditsAvailableCount > 0) ? true : false;
    }
});