<section id="footer-section" class="footer-section" style="display: none">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="footer-address">
                    <ul >
                        <li class="footer-contact"><i class="fa fa-home"></i><a href="#">Contact Us</a></li>
                        <li class="footer-contact"><i class="fa fa-envelope"></i><a href="#">Webmail</a></li>
                        <li class="footer-contact"><i class="fa fa-user"></i><a href="#">Career</a></li>
                    </ul>
                </div>
            </div><!--/.col-md-3 -->
            <div class="col-md-3">
                <div class="footer-address">
                    <iframe width="220" height="150" src="https://www.youtube.com/embed/XGSy3_Czz8k">
                    </iframe>
                </div>
            </div><!--/.col-md-3 -->

            <div class="col-md-2">
                <div class="section-heading-2">
                    <h3 class="section-title">
                        <span>Follow Us</span>
                    </h3>
                </div>

                <div class="social flickr-widget">
                    <ul class="flickr-list">
                        <li>
                            <img src="{{ asset('assets/website/images/uploads/socialMedia/facebook.png') }}" alt=""  class="img-responsive">
                        </li>
                        <li>
                            <img src="{{ asset('assets/website/images/uploads/socialMedia/google-plus.png') }}" alt="" class="img-responsive">
                        </li>
                        <li>
                            <img src="{{ asset('assets/website/images/uploads/socialMedia/twitter.png') }}" alt="" class="img-responsive">
                        </li>
                    </ul>
                </div>
            </div><!--/.col-md-3 -->

            <div class="col-md-2">
                <div class="section-heading-2">
                    <h3 class="section-title">
                        <span>Visitor Counter</span>
                    </h3>
                </div>

                <div class="footer-address">
                    <h2>123412341</h2> 
                </div>
            </div><!--/.col-md-3 -->
        </div><!--/.row -->
    </div><!-- /.container -->
</section>

<footer class="footer">
    <div class="container-fluid">
        <div class="row"> 
            
            <div class="col-md-4 col-sm-12 col-xs-12 text-center text-center">
                <div class="" style="height: 133px; border:2px solid white;margin-left:58px">
                    @php
                        $footerSilderImages = $footerSliderImage;
                    @endphp

                    @if($footerSilderImages)
                        <div id="carousel-example-generic1" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                @for($i = 0; $i < count($footerSilderImages); $i++)
                                    <li data-target="#carousel-example-generic1" data-slide-to="{{ $i }}" class="{{ $i == 0 ? 'active' : '' }}"></li>
                                @endfor
                            </ol>
                            <div class="carousel-inner" role="listbox">
                                @foreach($footerSilderImages as $i => $footerSilderImage)
                                    <div class="item {{ $i == 0 ? 'active' : '' }}">
                                        <img src="{{ asset('assets/images/websiteFooterImages/' . $footerSilderImage->image) }}" style="height: 130px;object-fit: contain">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="col-md-4 col-sm-12 col-xs-12 text-center footer-text footer-link">
                Jahurul Islam Memorial Complex <br>
                Plot: 152, Road: 4, Block: A, Niketan, Gulshan, Dhaka-1212<br>
                Phone: +88 02 9841563, 9859954<br>
                Email: office@niketansociety.org
                <br>
                <div class="divider" style="margin-top: 20px"><span></span></div>
                <br>
                <a class="complainText" href="{{ url('ContactUs/mailToComplain') }}">Complain By Mail</a>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12 text-center">
                <div class="flickr-widget">
                    <div style="overflow-x: auto">
                        <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fniketansociety.org%2F&amp;tabs=timeline&amp;width=340&amp;height=130&amp;small_header=false&amp;adapt_container_width=true&amp;hide_cover=false&amp;show_facepile=true&amp;appId" width="340" height="130" style="border:2px solid white;overflow:hidden" scrolling="no" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
                    </div>
                </div>
            </div>

        </div>
    </div>
</footer>

<div id="copyright-section" class="copyright-section">
    <div class="container">

        <div class="row text-center">
            <div class="col-md-6">
                <div class="copyright" style="color:white">
                    Copyright © Niketan Society {{ date('Y') }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="copyright" style="color:white">
                    Design & Developed by <a href="http://arrowlink.com.bd/soft.html"><b style="color:white">ArrowLink™ Soft</b></a>
                </div>
            </div>
        </div><!--/.row -->

    </div><!-- /.container -->
</div>