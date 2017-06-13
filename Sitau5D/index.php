<!DOCTYPE html>
<html>
<head>
    <?php 
        $urlParts = explode('/', $_SERVER['REQUEST_URI']);
        $page = $urlParts[1];
    ?>
    <title>Sitau5D consultancy - People, Process & Systems | Accounting & Fiscal, with care</title>
    <meta charset="UTF-8">
    <meta name="description" content="Sitau5D provides companies with advice and solutions in alignment with People, Process & Systems. Accounting & Fiscal, with care.">
    <meta name="keywords" content="accounting, fiscal, fiscaal, organisatieadviseur, consulting, consultant, consultancy">
    <meta name="author" content="Sitau5D">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>

<body>
    <nav>
        <a href="#home" class="small-logo"><img src="img/sitau5D-small-logo.png" alt="Sitau5D logo" title="Sitau5D" width="96px" height="23px" /></a>
        <ul class="navigation-menu">
            <li><a href="#services">Services</a></li>
            <li><a href="#about-us">About us</a></li>
            <li><a href="#collaborations">Collaborations</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </nav>
    <div class="container">

        <!-- WHAT -->
        <header id="home" class="color-1">
            <div class="header-image">
                <img src="img/sitau5D.png" class="header-logo" />
                <div class="header-slogan">
                    <p>Sitau5D provides advice and solutions in alignment with People, Process & Systems | Accounting & Fiscal, with care.</p>
                </div>
            </div>

            <table class="content">
                <tr>
                    <td class="list">
                        <h1>People</h1>
                        <ul>
                            <li>Teamworking in cross departmental projects and multidisciplinary teams</li>
                            <li>Accounting, Control & Fiscal</li>
                        </ul>
                    </td>
                    <td class="list">
                        <h1>Process</h1>
                        <ul>
                            <li>Process improvement and optimalization</li>
                            <li>Implementation</li>
                        </ul>
                    </td>
                    <td class="list">
                        <h1>Systems</h1>
                        <ul class="last-list">
                            <li>Functional support</li>
                            <li>Implementation</li>
                            <li>Change and Release Management</li>
                            <li>Project</li>
                        </ul>
                    </td>
                </tr>
            </table>
        </header>


        <!-- SERVICES -->
        <section id="services" class="color-2">
            <h1>Our Services</h1>
            <div class="content">
                <p>
                    Sitau5D is a consulting company specialised in improvement and optimalisation of financial processes, design system, analysis and support services to clients. With the key target of teamworking in collaboration with employees, customers and competitors besides theoretical information. Click on an icon below to read more about our services.
                </p>

                <div class="list-header-image-container">
                    <div class="list-header-image">
                        <a class="list-link" data-list-id="startup-beginning" href="#">
                            <img src="img/tree.png" />
                            <h2>Startup Beginning</h2>
                        </a>
                    </div>
                    <div class="list-header-image">
                        <a class="list-link" data-list-id="startup-checkup" href="#">
                            <img src="img/checkup.png" />
                            <h2>Startup Checkup</h2>
                        </a>
                    </div>
                    <div class="list-header-image">
                        <a class="list-link" data-list-id="accounting-fiscal" href="#">
                            <img src="img/graph.png" />
                            <h2>Accounting & Fiscal</h2>
                        </a>
                    </div>
                    <div class="list-header-image">
                        <a class="list-link" data-list-id="interim-consultancy" href="#">
                            <img src="img/cogs.png" />
                            <h2>Interim / Consultancy</h2>
                        </a>
                    </div>
                </div>
            </div>
            <div style="position:relative;clear:both">
            <?php 
                // List of services
                include_page('services-startup-beginning',   'services-startup-beginning');
                include_page('services-startup-checkup',     'services-startup-beginning');
                include_page('services-accounting-fiscal',   'services-startup-beginning');
                include_page('services-interim-consultancy', 'services-startup-beginning');
            ?>
            </div>
            <a href="#contact" class="action-button"><i class="fa fa-envelope"></i> <span>Contact us now</span></a>
        </section>


        <!-- QUOTE -->
        <section class="image-block quote-bg" style="display:table-cell; vertical-align:middle;">
            <blockquote>
                <p>
                    “Leadership is a matter of intelligence, trustworthiness, humaneness,
                    courage and sternness.”
                </p>
                <footer> 
                    <cite>— Sun Tzu, The Art of War</cite>
                </footer>
            </blockquote>
        </section>

        <!-- WHO -->
        <section id="about-us" class="color-3">
            <h1>Who is Sitau5D</h1>
            <div class="content">
                <img src="img/michel-arents.jpg" alt="Michel Arents" title="Michel Arents" class="profile-photo" width="335px" height="465px" />
                <section class="profile-description">
                    <header class="profile-header">
                        <h2>Michel Arents</h2>
                        <p>Founder, People, Process & Systems | Accounting and Fiscal, with care</p>
                    </header>
                    
                    <p>Combination of Cantonese word Sitau, which means boss / Chinese word tau is the foundation for 
                    spiritual self-cultivation, and Cantonese 5D is pronounced as Faai Di, which means hurry-up.</p>
                    
                    <p>Sitau5D plays a leader role in ERP implementation and coordinates projects and changes in the area 
                    of finance and cross departmental projects with multidisciplinary teams.</p>
                    
                    <p>My name is Michel, a professional with 12+ years of experience as a Senior Functional Application 
                    Engineer, Reporting Analyst, BI Specialist and Finance Specialist for a world leading marine 
                    contractor in the offshore oil and gas industry, a top-tier independent Dutch law firm and one of the 
                    big five consultancy firms in the world with a strong focus on strategic and operational tasks.</p>
                    
                    <p>I am able to make the transformation from systems to processes and vica versa. Focus on process 
                    and systems from the combination of work experience and Fiscal Economics and Entrepreneurship 
                    Business School as theoretical background with additional courses in BPM, SAP, Excel and SQL. I 
                    have embedded critical thinking skills and leadership through the study Culture, Organization and 
                    Management at the VU University.</p>
                </section>
            </div>
        </section>


        <!-- WHO WE WORK WITH -->
        <section id="collaborations" class="color-2">
            <h1>Who we work with</h1>
            <div class="content">
                <p style="margin-bottom:20px;">
                    Sitau5D is a consulting company in improvement and optimalization of financial processes, design 
                    system, analysis and support services to clients.
                    With the key target of teamworking in collaboration with employees, customers and competitors 
                    besides theoretical information.
                </p>
                <div id="collab-description-container">
                    <div class="collab-description">
                        <div class="collab-logo-container">
                            <img id="collab-logo" src="img/logos/sitau5D.png" alt="logo" />
                        </div>
                        <div id="collab-properties">
                            <h2 id="collab-cat">View Clients & Partners</h2>
                            <p id="collab-name">Click on a logo for more info.</p>
                            <a href="" id="collab-url"></a>
                        </div>
                    </div>
                </div>
                <div id="graph"></div>
            </div>
        </section>


        <!-- CONTACT -->
        <section id="contact" class="color-4 contact-bg">
            <div class="content">
                <h1>Need some work done?</h1>
                <ul class="social-media-list">
                    <li><a href="mailto:info@sitau5d.nl"><i class="fa fa-envelope-square fa-lg"></i> info@Sitau5D.nl</a></li>
                    <li><a href="http://nl.linkedin.com/in/michelarents"><i class="fa fa-linkedin-square fa-lg"></i> Michel Arents</a></li>
                    <li><a href="https://twitter.com/Sitau5D"><i class="fa fa-twitter-square fa-lg"></i> @Sitau5D</a></li>
                    <li><a href="https://www.facebook.com/Sitau5D-583181671824792"><i class="fa fa-facebook-square fa-lg"></i> Sitau5D</a></li>
                    <li><a href="https://plus.google.com/112451210532310546064"><i class="fa fa-google-plus-square fa-lg"></i> Sitau5D</a></li>
                </ul>

                <form method="post" action="#contact" class="contact-form">
                    <input type="hidden" name="action" value="sendmail" />
                    <ul>
                        <li class="status-message"><?php if($_POST['action']) { echo 'Thank You. Your message has been sent.'; }  ?></li>
                        <li><input type="text" placeholder="Name" name="name" class="form-element" /></li>
                        <li><input type="text" placeholder="Email" name="email" class="form-element" /></li>
                        <li><textarea placeholder="Message" name="message" class="form-element"></textarea></li>
                        <li><button type="submit" class="form-element">Send</button></li>
                    </ul>
                </form>
            </div>
        </section>

        <div class="footer"> KVK: 63547473 - © <?php echo date('Y'); ?> Sitau5D</div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>
    <script src="http://code.jquery.com/jquery-1.11.1.js" ></script>
    <script type="text/javascript" src="graph.js"></script>
    <script type="text/javascript">
        $(document).ready(function()
        {
          $(function() {
            $('a[href*="#"]:not([href="#"])').click(function() {
              if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
                if (target.length) {
                  $('html, body').animate({
                    scrollTop: target.offset().top-35
                  }, 1000);
                  return false;
                }
              }
            });
          });

            $('a[data-list-id]').click(function()
            {
                var id = $(this).attr('data-list-id');
                var allLists = $('table[data-list-id]');
                var selectedList = $('table[data-list-id='+id+']');
                var speed = 500;

                allLists.not( selectedList ).fadeOut( speed );
                selectedList.addClass('absolute').fadeIn({duration:speed,complete:function()
                    {
                        $(this).removeClass('absolute');}
                    });
                
                return false;

            });

        });
    </script>
</body>

</html>

<?php
    include('sendmail.php');

    // Include page content
    function include_page($page, $standard='home') {
        $page      = htmlspecialchars($page, ENT_QUOTES).'.php';
        $directory = 'content/';

        if(!empty($page) && file_exists($directory.$page)) {
            include($directory.$page);
        } else {
            include($directory.$standard.'.php');
        }
    }
?>