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
        .content-div {
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
        function printdiv(printpage) {
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
    <div class="full-page">
        <div id="my-div" class="my-div">
            <style>
                .content-div {
                    width: 735px;
                    font-family: Calibri;
                    background-color: #FFF;
                }

                .footer {
                    text-align: center;
                    font-family: Calibri;
                    font-size: 12px;
                    margin-top: -5px;
                }

                .heading h2 {
                    height: 10px;
                    margin-top: 5px;
                }

                .heading {
                    margin: auto;
                    width: 440px;
                }

                .heading h2 {
                    height: 10px;
                    margin-top: 5px;
                }


                .text-left {
                    text-align: left!important;
                    padding-left: 5px;
                }

                .member-table{
                    border: 1px solid #b9b9b9;
                    border-collapse: collapse;
                }

                .member-table th,
                .member-table td {
                    border: 1px solid #b9b9b9;
                    padding: 5px;
                    text-align: center;

                }
                

            </style>
            <div id="" class="content-div">
                <table class="table member-table">
                    
                    <tr style="background-color: #3c763d;color:white">
                        <th class="text-center">SL</th>
                        <th class="text-center" style="width: 100px">Image</th>
                        <th style="width: 275px">Name</th>
                        <th style="width: 325px">Address</th>
                        <th class="text-center">Member Id</th>
                    </tr>
                    
                    <?php
                    $sl = 1;
                    foreach ($personalInformations as $member) {
                        $imageUrl = asset('assets/images/user.png');
                        if ($member->member_image && file_exists(public_path('storage/member/' . $member->member_image))) {
                            $imageUrl = asset('storage/member/' . $member->member_image);
                        }
                        ?>
                        
                        <tr style="background-color: white">
                            <td class="vertical-middle"><?php echo $sl; ?></td>
                            <td><img src="<?php echo $imageUrl ?>" style="width:80px"></td>
                            <td class="vertical-middle" style="white-space:normal; overflow-wrap:break-word; word-break:break-word">
                                {{ $member->member_name }}
                            </td>

                            <td class="text-left vertical-middle" style="white-space:normal; overflow-wrap:break-word; word-break:break-word">
                                <p>
                                    <?php
                                    echo "Flat: " .
                                        ($member->society_flat ? $member->society_flat : "N/A") .
                                        ", Plot/House: " .
                                        ($member->society_plot ? $member->society_plot : "N/A") .
                                        ", Road: " .
                                        ($member->road_name ? $member->road_name : "N/A") .
                                        ", Block: " .
                                        ($member->block_name ? $member->block_name : "N/A") .
                                        ", Niketan, Gulshan, Dhaka-1212";
                                    ?>
                                </p>
                            </td>
                            <td class="text-center vertical-middle"
                                style="width: 100px"><?php 
                                $memberidstr="";
                                If($printMemberId=="both"){
                                    $memberidstr=$member->member_id;
                                    If($member->donar_member_id){$memberidstr= $memberidstr."<br>".$member->donar_member_id;}
                                }
                                elseif($printMemberId=="member_id"){
                                    $memberidstr=$member->member_id;
                                }
                                elseif($printMemberId=="donar_member_id"){
                                    $memberidstr=$member->donar_member_id;
                                }
                                echo $memberidstr; 
                            ?></td>
                        </tr>

                        <?php $sl++;
                    } ?>
                    
                </table>
            </div>
        </div>
    </div>
    <br>
</div>

</body>
</html>