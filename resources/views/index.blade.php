<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PMS Medical Complex </title>
  <link rel="icon" type="image/x-icon" href="{{asset('public/assets/images/brand/favicon.ico')}}" />


  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans|Raleway|Candal">
  <link rel="stylesheet" type="text/css" href="{{asset('public/website/css/font-awesome.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('public/website/css/bootstrap.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('public/website/css/style.css')}}">

  <!-- =======================================================
    Theme Name: Medilab
    Theme URL: https://bootstrapmade.com/medilab-free-medical-bootstrap-theme/
    Author: BootstrapMade.com
    Author URL: https://bootstrapmade.com
  ======================================================= -->
</head>

<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
  <!--banner-->
  <section id="banner" class="banner">
    <div class="bg-color">
      <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
          <div class="col-md-12">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				      </button>
              <a class="navbar-brand" href="#"><img src="{{asset('public/pms.png')}}"
                 class="img-responsive" style="width: 140px; margin-top: -16px;"></a>
                 <!--<img src="" class="header-brand-img" alt="">-->
                 
            </div>
            <div class="collapse navbar-collapse navbar-right" id="myNavbar">
              <ul class="nav navbar-nav">
                <li class="active"><a href="#banner">Home</a></li>
                <li class=""><a href="#service">Services</a></li>
                <li class=""><a href="#about">About</a></li>
                <li class=""><a href="#contact">Contact</a></li>
                @if(Auth::check())
                <li class="" style="background-color:#847cff"><a href="{{url('dashboard')}}">Dashboard</a></li>
                <li class=""style="background-color:#d82c2c" ><a href="{{url('logout')}}">Logout</a></li>
                @else
                <li class="" style="background-color:#847cff"><a href="{{url('login')}}">Login</a></li>
                @endif
              </ul>
            </div>
          </div>
        </div>
      </nav>
      <div class="container">
        <div class="row">
          <div class="banner-info">
         
            <div class="banner-text text-center">
              <h1 class="white">Healthcare at your desk!!</h1>

              <a href="#contact" class="btn btn-appoint">Make an Appointment.</a>
            </div>
            <div class="overlay-detail text-center">
              <a href="#service"><i class="fa fa-angle-down"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--/ banner-->
    <section id="about" class="section-padding">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="section-title">
            <h2 class="head-title lg-line">About Us</h2>
            <hr class="botm-line">
                <p class="sec-para">Professor Morwarid Safi Curative Hospital (PMSCH) is your personal health care facility, and we are here to help you and your family with our high-quality services and physicians.
    PMSCH’s mission has remained consistent since its inception: to provide modern preventive and curative healthcare services to all patients, regardless of cast, color, or creed, and regardless of ability to pay, in a value-added environment full of care and compassion by an exceptional team of healthcare professionals in extraordinary ways.
    </p>
    <h4>Our Mission</h4>
<p class="sec-para">To deliver advanced preventative and curative health care services to all patients, regardless of caste, race, or creed, or ability to pay, in a value-added atmosphere filled with care and compassion by an extraordinary team of health care experts.</p>

<h4>Our Vision</h4>
<p class="sec-para">To go far beyond excellence in healthcare services by becoming Afghanistan's leading health organization, offering a wide variety of products, services, and reliability that meets the highest patient's needs.</p> 


          </div>
        </div>
        <div class="col-md-6">
          <img class="img-fluid" src="{{url('public/ap.jpg')}}" style="max-width: 100%;height: auto;margin-top:85px" >
        </div>
      </div>
    </div>
  </section>
  <!--service-->
  <section id="service" class="section-padding">
    <div class="container">
       <h2 class="ser-title">Our Service</h2>
        <hr class="botm-line">
          
      <div class="row">
    
        <div class="col-md-4 col-sm-4">
          <div class="service-info">
            <div class="icon">
              <i class="fa fa-book"></i>
            </div>
            <div class="icon-info">
              <h4>Reception</h4>
              <p>With a dedicated team on the first floor main lobby, PMSCH Reception is the key to accessing our hospital services and information. The reception team performs the following tasks on a daily basis for all patients/visitors at PMSCH (overall hospital information, consultant appointments, billing, and lab reports).</p>
            </div>
          </div>
          <div class="service-info" style="margin-top: 70px;">
            <div class="icon">
              <i class="fa fa-ambulance"></i>
            </div>
            <div class="icon-info">
              <h4>Ambulance</h4>
              <p>The PMSCH transportation department has modern and well-equipped ambulances for inpatient and outpatient transportation within Nangarhar and, in rare cases, to other provinces of Afghanistan.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-4">
          <div class="service-info">
            <div class="icon">
              <i class="fa fa-flask"></i>
            </div>
            <div class="icon-info">
              <h4>Laboratory</h4>
              <p>PMSCH has created one of the best diagnostics centers in order to excel in the field of health. We maintain our commitment to service, quality, and accuracy because we understand the importance of accurate and timely diagnosis.</p>
            </div>
          </div>
          <div class="service-info" style="margin-top: 110px;">
            <div class="icon">
              <i class="fa fa-stethoscope"></i>
            </div>
            <div class="icon-info">
              <h4>Emergency Care Services</h4>
              <p>PMSCH's Emergency Care Services include patients referred from other government and non-government hospitals as well as routine patients. Our emergency services cover a wide range of specialties. Palliative care is provided to all patients. The emergency department is outfitted with all of the necessary equipment, as well as physicians and nurses who are trained and experienced in emergency medicine to ensure patient care and treatment.</p>
            </div>
          </div>
          
        </div>
         <div class="col-md-4 col-sm-4">
          <div class="service-info">
            <div class="icon">
              <i class="fa fa-plus-square"></i>
            </div>
            <div class="icon-info">
              <h4>Pharmacy</h4>
              <p>The Department of Pharmacy Services at PMSCH provides cutting-edge pharmacy services with the goal of providing our customers with compassionate, ethical, accessible, and high-quality pharmacy services. The Department takes pride in providing high-tech, cutting-edge professional services to our patients, both in-patients and ambulatory care patients, 24 hours a day, seven days a week.</p>
            </div>
          </div>
          <div class="service-info" style="margin-top:27px;">
            <div class="icon">
              <i class="fa fa-coffee"></i>
            </div>
            <div class="icon-info">
              <h4>Cafeteria & Clinical Nutrition</h4>
              <p>The PMSCH Cafeteria serves healthy and balanced food to both patients and visitors on a 24-hour basis throughout the year. Nutrition refers to a well-balanced and well-composed diet that contains all of the basic nutrients required by the human body to function. Patients can choose healthy food from a variety of menus that have been specially designed for them. Each ingredient is chosen with care .</p>
            </div>
          </div>
      </div>
    </div>
  </section>
  <!--/ service-->

  
  <!--cta-->
  <!--contact-->
  <section id="cta-2"  class="section-padding">
    <div class="container"  id="contact">
      <div class="row">
        <div class="col-md-12">
          <h2 class="text-white"  style="color:#ffffff !important">Contact us</h2>
          <hr class="botm-line">
        </div>
        <div class="col-md-4 col-sm-4">
          <h3  style="color:#ffffff !important">Contact Info</h3>
          <div class="space"></div>
          <p  style="color:#ffffff !important"><i class="fa fa-map-marker fa-fw pull-left fa-2x"></i>Opposite Haji Sahib Gul Karim Center, Next to Tribal Directorate , 1st Zone,
            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Professor Morwarid Safi Curative &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hospital</p>
          <div class="space"></div>
          <p  style="color:#ffffff !important"><i class="fa fa-envelope-o fa-fw pull-left fa-2x"></i>info@pmsmedicalcomplex.com</p>
          <div class="space"></div>
          <p  style="color:#ffffff !important" ><i class="fa fa-phone fa-fw pull-left fa-2x"></i>+93 78 55555 44</p>
        </div>
        <div class="col-md-8 col-sm-8 marb20">
          <div class="contact-info">
            <h3 class="cnt-ttl"  style="color:#ffffff !important">Book an appointment</h3>
            <div class="space"></div>
            <div id="sendmessage">Your message has been sent. Thank you!</div>
            <div id="errormessage"></div>
            <form action="" method="post" role="form" class="contactForm">
              <div class="form-group">
                <input type="text" name="name" class="form-control br-radius-zero" id="name" placeholder="Your Name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" />
                <div class="validation"></div>
              </div>
              <div class="form-group">
                <input type="email" class="form-control br-radius-zero" name="email" id="email" placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email" />
                <div class="validation"></div>
              </div>
              <div class="form-group">
                <input type="text" class="form-control br-radius-zero" name="subject" id="subject" placeholder="Subject" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" />
                <div class="validation"></div>
              </div>
              <div class="form-group">
                <textarea class="form-control br-radius-zero" name="message" rows="5" data-rule="required" data-msg="Please write something for us" placeholder="Message"></textarea>
                <div class="validation"></div>
              </div>

              <div class="form-action">
                <button type="submit" class="btn btn-form">Send Message</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--/ contact-->
  <!--footer-->
  <footer id="footer">
    <div class="top-footer">
      <div class="container">
        <div class="row">
          <div class="col-md-4 col-sm-4 marb20">
            <div class="ftr-tle">
              <h4 class="white no-padding">About Us</h4>
            </div>
            <div class="info-sec">
              <p>Professor Morwarid Safi Curative Hospital (PMSCH) is your personal health care facility, and we are here to help you and your family with our high-quality services and physicians.</p>
            </div>
          </div>
        <div class="col-md-4 col-sm-4 mt-boxy-3">
              <h4 class="white no-padding">Opening Hours</h4>
                
              <table style="margin: 28px 0px 0px;" border="1">
                <tbody>
                  <tr>
                    <td>Sturday- Thursday</td>
                    <td>6.00 am - 7.00 pm</td>
                  </tr>
                  <tr>
                    <td>Friday</td>
                    <td>8.00 am - 4.00 pm</td>
                  </tr>
                </tbody>
              </table>
          </div>
          
          <div class="col-md-4 col-sm-4 marb20">
            <div class="ftr-tle">
              <h4 class="white no-padding">Follow us</h4>
            </div>
            <div class="info-sec">
              <ul class="social-icon">
                <li class="bglight-blue"><i class="fa fa-facebook"></i></li>
                <li class="bgred"><i class="fa fa-google-plus"></i></li>
                <li class="bgdark-blue"><i class="fa fa-linkedin"></i></li>
                <li class="bglight-blue"><i class="fa fa-twitter"></i></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-line">
      <div class="container">
        <div class="row">
          <div class="col-md-12 text-center">
            © Copyright pmsmedicalcomplex.com. All Rights Reserved
            <div class="credits">
            
              Designed by <a href="https://sarey.co/">Sarey Solution</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!--/ footer-->

  <script src="{{asset('public/website/js/jquery.min.js')}}"></script>
  <script src="{{asset('public/website/js/jquery.easing.min.js')}}"></script>
  <script src="{{asset('public/website/js/bootstrap.min.js')}}"></script>
  <script src="{{asset('public/website/js/custom.js')}}"></script>
  <script src="{{asset('public/website/contactform/contactform.js')}}"></script>

</body>

</html>
