<html>
    <head>
        <style type="text/css">
            @media print {
                div.newPageDivClass {
                    page-break-after: always;
                }
            }
            /*            .full-page{
                            width: 788px;
                            height: 1113px;
                        }*/
            .content-div{
                width: 50%;
                margin: auto; 
                padding: 10px 20px 10px 20px;
            /*                border: 1px solid black;*/
            }
            body {
                -webkit-print-color-adjust: exact;
            }
        </style>  
        <script type="text/javascript">
            function printdiv(printpage)
            {
                var headstr = "<html><head><title></title>";
                var style = "<link rel='stylesheet' href='' type='text/css' />";
                var headstr1 = "</head><body>";
                var footstr = "</body>";
                var newstr = document.getElementById(printpage).innerHTML;
                var oldstr = document.body.innerHTML;
                document.body.innerHTML = headstr + style + headstr1 + newstr + footstr;
                window.print();
                document.body.innerHTML = oldstr;
                return false;
            }
        </script>


    </head>
    <body style="background-color: #eee;">

        <input name="b_print" type="button" onClick="printdiv('my-div');" value=" Print ">
    </div>   
    <br>
    <div>
        <div class="full-page" >
            <div id="my-div" class="my-div">
                <style>
                    .content-div{
                        width: 735px;
                        font-family:Calibri;
                        background-color: #FFF;
                    }

                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f5f5f5;
                        padding: 20px;
                    }

                    .card {
                        background: white;
                        padding: 20px;
                        max-width: 500px;
                        margin: auto;
                    }
                    .header {
                        font-size: 26px;
                        font-weight: bold;
                        color: #ff4081;
                        text-align: center;
                    }
                    .couple-name {
                        font-size: 20px;
                        font-weight: bold;
                        color: #333;
                        margin-top: 20px;
                    }
                    .message {
                        font-size: 16px;
                        margin: 15px 0;
                        color: #555;
                    }
                    .footer {
                        font-size: 16px;
                        color: #777;
                        font-weight: bold;
                    }
                </style>

                @foreach ($personalInformations as $personalInformation)
                    <div id="" class="content-div" >

                        <div class="card">
                            <div class="header"> Happy Marriage Anniversary!</div>
                            <div class="couple-name">Dear {{ $personalInformation->member_name }},</div>
                            <div class="message">
                            The Executive Committee of Niketan Society wishing you both a very HAPPY ANNIVERSARY. 
                            May your journey together continue to be filled with love, joy, and beautiful memories. Here’s to many more years of happiness and togetherness.
                            </div>
                            <br>
                            <div class="footer"> Warmest wishes,
                                <br><br><br><br>
                                <div>
                                    <div style="float: left;">
                                        Prof Dr Md Abul Bashar <br>
                                        President

                                    </div> 
                                    <div style="float: right;">
                                        Rezaul Karim Chowdhury Arif <br>
                                        General Secretary

                                    </div>    
                                </div> 
                            </div>                       
                        </div>
                        <br>
                        <br>
                        <br>
                    </div>

                    <br>
                    <div class="newPageDivClass"></div>
                @endforeach


            </div>  
        </div>
        <br>
    </div>

</body>
</html>