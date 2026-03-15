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
                        text-align: center;
                        padding: 20px;
                    }

                    .card {
                        background: white;
                        padding: 20px;
                        border-radius: 10px;
                        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                        max-width: 500px;
                        margin: auto;
                        border: 3px solid #ff4081;
                    }
                    .header {
                        font-size: 26px;
                        font-weight: bold;
                        color: #ff4081;
                    }
                    .couple-name {
                        font-size: 22px;
                        font-weight: bold;
                        color: #333;
                        margin-top: 10px;
                    }
                    .message {
                        font-size: 18px;
                        margin: 20px 0;
                        color: #555;
                    }
                    .footer {
                        font-size: 16px;
                        color: #777;
                    }
                    .hearts {
                        font-size: 24px;
                        color: #ff4081;
                    }


                </style>

                @foreach ($personalInformations as $personalInformation)
                    <div id="" class="content-div" >

                        <div class="card">
                            <div class="header"> Happy Birthday !</div>
                            <div class="couple-name">{{ $personalInformation->employee_name }}</div>
                            <div class="message">
                                Wishing you a spectacular birthday filled with joy, laughter, and love!<br>
                                May this special day bring you happiness, good health, and countless beautiful moments.
                                Enjoy your day to the fullest! 🎂🎁🎈
                            </div>
                            <div class="footer">- Warm wishes from Niketan Society</div>
                            <div class="hearts">💕💕💕</div>
                        </div>
                        
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