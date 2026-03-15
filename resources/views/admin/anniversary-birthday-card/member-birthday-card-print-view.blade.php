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
                var newstr = document.all.item(printpage).innerHTML;
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
                        /* text-align: center; */
                        padding: 20px;
                    }

                    .card {
                        background: white;
                        padding: 20px;
                        /* border-radius: 10px;
                        box-shadow: 0 4px 8px rgba(0,0,0,0.1); */
                        max-width: 500px;
                        margin: auto;
                        /* border: 3px solid #ff4081; */
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
                            <div class="header"> Happy Birthday !</div>
                            <div class="couple-name">{{ $personalInformation->member_name }}</div>
                            <div class="message">
                            The Executive Committee of Niketan Society wishing you a fantastic BIRTHDAY filled with love, laughter, and all the happiness you deserve. May this special day bring you unforgettable moments and the year ahead be full of new adventures, growth, and joy.
                            </div>
                            <br>
                            <div class="footer">Here’s to celebrating YOU today and always
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