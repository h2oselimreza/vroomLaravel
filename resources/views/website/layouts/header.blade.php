<header class="clearfix">
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center">
        <img src="{{ asset('assets/images/company/company_logo.png') }}" style="margin:10px;width:15%">
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="navbar navbar-default navbar-top">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <i class="fa fa-bars"></i>
            </button>
          </div>
          <div class="navbar-collapse collapse" style="padding-left:0px;padding-right:0px;box-shadow:1px 0px 9px 0px rgba(53,50,50,0.85);">
            <ul class="nav navbar-nav">
              <li>
                <a href="{{ url('/') }}">Home</a>
              </li>
              <li>
                <a href="#">About Us</a>
                <ul class="dropdown">
                  <li>
                    <a href="{{ url('about-society') }}">About Society</a>
                  </li>
                  <li>
                    <a href="{{ url('history-of-society') }}">History of Society</a>
                  </li>
                  <li>
                    <a href="{{ url('message-from-president') }}">Message from President</a>
                  </li>
                  <li>
                    <a href="{{ url('message-from-general-secretary') }}">Message from General Secretary</a>
                  </li>
                  <li>
                    <a href="{{ url('message-from-office-secretary') }}">Message from Office Secretary</a>
                  </li>
                  <li>
                    <a href="{{ url('message-from-pnp-secretary') }}">Message From P&P Secretary</a>
                  </li>
                  <li>
                    <a href="{{ url('mission-vision') }}">Mission & Vision</a>
                  </li>
                  <li>
                    <a href="{{ url('campaign') }}">Campaign</a>
                  </li>
                  <li>
                    <a href="{{ url('about/achievements') }}">Achievements</a>
                  </li>
                </ul>
              </li>
              <li>
                <a href="#">Committee</a>
                <ul class="dropdown">
                  <li>
                    <a href="{{ url('present-executive-committee') }}">Present Executive Committee</a>
                  </li>
                  <li>
                    <a href="{{ url('present-sub-committee') }}">Present Sub Committee</a>
                  </li>
                  <li>
                    <a href="{{ url('adviser-comittee') }}">Adviser Comittee</a>
                  </li>
                  <li>
                    <a href="{{ url('central-mosque-committee') }}">Central Mosque Committee</a>
                  </li>
                </ul>
              </li>
              <li>
                <a href="{{ url('show-facilities') }}">Facilities</a>
              </li>
              <li>
                <a href="{{ url('event') }}">Events</a>
              </li>
              <li>
                <a href="#">Meeting</a>
                <ul class="dropdown">
                  <li>
                    <a href="{{ url('ec-meetings') }}">EC Meetings</a>
                  </li>
                  <li>
                    <a href="{{ url('agm') }}">AGM</a>
                  </li>
                  <li>
                    <a href="{{ url('gm') }}">GM</a>
                  </li>
                </ul>
              </li>
              <li>
                <a href="#">Archive</a>
                <ul class="dropdown">
                  <li>
                    <a href="{{ url('previous-executive-committee') }}">Previous Executive Committee</a>
                  </li>
                  <li>
                    <a href="{{ url('previous-president') }}">Previous President</a>
                  </li>
                  <li>
                    <a href="{{ url('previous-general-secretary') }}">Previous General Secretary</a>
                  </li>
                </ul>
              </li>
              <li>
                <a href="#">Maps</a>
                <ul class="dropdown">
                  <li>
                    <a href="{{ url('a-block') }}">A Block Map</a>
                  </li>
                  <li>
                    <a href="{{ url('b-block') }}">B Block Map</a>
                  </li>
                  <li>
                    <a href="{{ url('c-block') }}">C Block Map</a>
                  </li>
                  <li>
                    <a href="{{ url('d-block') }}">D Block Map</a>
                  </li>
                  <li>
                    <a href="{{ url('e-block') }}">E Block Map</a>
                  </li>
                  <li>
                    <a href="{{ url('f-block') }}">F Block Map</a>
                  </li>
                  <li>
                    <a href="{{ url('g-block') }}">G Block Map</a>
                  </li>
                </ul>
              </li>
              <li>
                <a href="{{ url('Home/showGallary') }}">Gallery</a>
              </li>
              <li>
                <a href="#">Members</a>
                <ul class="dropdown">
                  <li>
                    <a href="{{ url('apply-member-ship') }}">Apply For Membership</a>
                  </li>
                  <li>
                    <a href="{{ url('apply-for-car-sticker') }}">Apply For Car Sticker</a>
                  </li>
                  <li>
                    <a href="{{ url('life-members') }}">Life Members</a>
                  </li>
                  <li>
                    <a href="{{ url('donar-members') }}">Donor Members</a>
                  </li>
                </ul>
              </li>
              <li>
                <a href="#">Career</a>
                <ul class="dropdown">
                  <li>
                    <a href="{{ url('vacancy') }}">Vacancy</a>
                  </li>
                  <li>
                    <a href="{{ url('career-result') }}">Career Result</a>
                  </li>
                  <li>
                    <a href="{{ url('advertisement') }}">Advertisement</a>
                  </li>
                </ul>
              </li>
              <li>
                <a href="#">Download</a>
                <ul class="dropdown">
                  <li>
                    <a href="{{ url('letters') }}">Letters</a>
                  </li>
                  <li>
                    <a href="{{ url('forms') }}">Forms</a>
                  </li>
                </ul>
              </li>
              <li>
                <a href="{{ url('show-tender') }}">Tender</a>
              </li>
              <li>
                <a href="{{ url('/login') }}">Login</a>
              </li>
              <li>
                <a href="{{ url('ContactUs') }}">Contact</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
<br>