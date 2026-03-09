@extends('website.layouts.single-page')
@section('main-content')
<div class="col-md-9">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
     <div class="heading-custom-panel" style="">
<br>
         <div class="text-center">
             <h3>Contact With Us</h3>
         </div>
         <br>
        <div class="custom-panel-body">
            <div class="text-justify" style="">
               <form action="{{ route('website.contact-us.contactUsMailSend') }}" method="POST">
                   @csrf
                   <div class="row">
                        <div class="col-md-6 wow fadeInLeft" data-wow-duration="2s" data-wow-delay="600ms">
                            
                            <div class="form-group">
<!--                                <label>Your Name</label>-->
                                <input type="text" class="form-control" placeholder="Your Name" name="name" required >

                            </div>
                            <div class="form-group">
<!--                                <label>Your Email</label>-->
                                <input type="email" class="form-control" placeholder="Your Email" name="fromMail" required >

                            </div>
                            <div class="form-group">
<!--                                 <label>Mail Heading</label>-->
                                <input type="text" class="form-control" placeholder="Mail Heading" name="mailHeading" required >

                            </div>
                        </div>
                        <div class="col-md-6 wow fadeInRight" data-wow-duration="2s" data-wow-delay="600ms">
                            <div class="form-group">
<!--                                <label>Mail Body</label>-->
                            <textarea class="form-control" placeholder="Mail Body" rows="6" name="mailBody" required ></textarea>

                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-lg-12 text-center wow zoomIn" data-wow-duration="1s" data-wow-delay="600ms">
                            <input type="submit" class="btn btn-primary" value="Send"> 
                        </div>
                    </div>
               </form>
               <br>
                <div class="row">
                    <div class="col-md-12" >
                        
                        <div style="border:2px solid black;height: 300px">
                            <!--<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7302.442792111406!2d90.41224309524536!3d23.775129240150743!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xe6f30cd1cfd6929f!2sNiketan+Welfare+Society!5e0!3m2!1sen!2sbd!4v1516088204943" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>-->
                            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14604.909563318442!2d90.4112953!3d23.7749157!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x83384df4054b218d!2sJahurul+Islam+Memorial+Complex!5e0!3m2!1sen!2sbd!4v1516169785152" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12" >
                        <div style="background-color: #eee;height: 180px;text-align: center;padding: 15px">
                            <h3>Our Address</h3>    
                            Jahurul Islam Memorial Complex <br>
                            Plot: 152, Road: 4, Block: A, Niketan, Gulshan, Dhaka-1212<br>
                            Phone: +88 02 9841563, 9859954<br>
                            Email: office@niketansociety.org<br>
                            Web: www.niketansociety.org
                            <!--House-8, Main Road,<br> Block-E, Banasree,<br> Rampura, Dhaka.<br> Phone : 8396661 <br> info@nievs.com.bd<br> www.nievs.com.bd-->
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
     </div>
</div>
@endsection