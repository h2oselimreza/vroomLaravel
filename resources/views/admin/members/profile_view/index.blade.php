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
                    .footer{
                        text-align: center;
                        font-family:Calibri;
                        font-size:12px;
                        margin-top: -5px;
                    }
                    .heading h2{ height: 10px;margin-top: 5px;}
                    .heading{margin:auto;width: 440px;}
                    .heading h2{ height: 10px;margin-top: 5px;}
                    .school-logo{text-align: center;}
                    .school-name{text-align: center;float: right}
                    .signature-footer{width: 100%;display: inline-block;padding: 40px 0px 0px 0px;}
                    .left_signature{float: left;margin-left: 30px;}
                    .right_signature{float: right;margin-right: 30px;}
                    .generated-footer{
                        font-size: 12px;
                        width: 100%;
                        display: inline-block;
                        padding: 0px 0px 0px 0px;
                    }
                    
                    .text-left{
                        text-align:left;
                        padding-left: 5px;
                    }

                    .heading-name
                    {	

                        font-size:16px;
                        font-family:Verdana, Geneva, sans-serif;
                        font-weight:bold;
                        color:#236313;
                        padding-left:7px;
                        padding-top:16px;
                        padding-bottom:1px;
                    }
                    .table-td-info
                    {	
                        background:#FFFFFF;
                        font-size:11px;
                        font-family:Verdana, Geneva, sans-serif;    
                        font-weight:normal;
                        padding-left:7px;
                        padding-top:2px;
                        padding-bottom:2px;
                    }
                    .div-separator{margin-top: 10px;margin-bottom: 10px;height: 20px;background-color: #eee;font-size: 13px;font-weight: bold;font-family:Verdana, sans-serif;padding: 6px 0px 3px 9px}
                    .content-table-td{height: 20px;padding-left: 5px;}
                    
                    .working-exp{font-size:11.5px;font-family:Verdana, Geneva, sans-serif;font-weight:normal;padding-left:7px;padding-bottom: 10px }
                    .working-exp-table{font-size:11px;font-family:Verdana, Geneva, sans-serif;padding-left:24px;padding-bottom: 5px;}
                    .education-qualification  {border-collapse:collapse;}
                    .education-qualification td{font-family:Verdana, Geneva, sans-serif;font-size:11px;padding:0px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;height: 45px;text-align: center}
                    .education-qualification th{font-family:Verdana, Geneva, sans-serif;font-size:11px;font-weight:normal;height:21px;border-style:solid;border-width: 1px 1px 2px 1px;font-weight: bold;height: 30px}
                </style>
                <?php foreach ($personalInformations as $personalInformation) { ?>
                    <div id="" class="content-div" >
                        <div class="heading">
                            <div class="school-logo">
                                <img src="{{ asset('assets/images/company/company_logo.png') }}" style="width:100px;">
                            </div>
                            <!-- <div class="school-name">
                                <h2><?php echo "COMPANY_NAME"; ?></h2>
                                Member Profile
                            </div> -->
                        </div>
                        <table border="0" cellpadding="0" cellspacing="0" align="center" width="750" >
                            <tr>
                                <td colspan="6">
                                    <table border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td width="73%" height="" align="left" valign="bottom" class="heading-name" style="padding-left:12px">
                                                <?php echo $personalInformation->member_name ?>
                                                <br><span style="font-size: 12px;line-height: 30px"><?php echo $personalInformation->member_bangla_name ?></span>

                                            </td>
                                            <td width="27%" rowspan="2" align="right" valign="bottom">
                                                <table width="140" height="140" border="0" align="center" cellpadding="0" cellspacing="7" bgcolor="<?php /*echo $bgcolor1*/ ?>">
                                                    <tr> 
                                                        <td width="126" height="135" align="center" bgcolor="<?php /*echo $bgcolor2*/ ?>" valign="middle"> <!-- bgcolor variable e nite hbe-->
                                                            <?php if ($personalInformation->member_image) { ?>
                                                                <img src="{{ asset('storage/member/' . $personalInformation->member_image) }}" width="124" height="135">
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="table-td-info" align="left" valign="middle">
                                                
                                                <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                                                    <tr class="table-td-info">
                                                        <td width="20%" align="left" class="content-table-td"><b>Member ID</b></td>
                                                        <td width="2%" align="center">:</td>
                                                        <td width="66%" align="left">
                                                            <?php echo $personalInformation->member_id ?>
                                                        </td>
                                                    </tr>
                                                    <?php 
                                                    if($personalInformation->donar_member_id){ ?>
                                                    <tr class="table-td-info">
                                                        <td width="20%" align="left" class="content-table-td"><b>Donar Member ID</b></td>
                                                        <td width="2%" align="center">:</td>
                                                        <td width="66%" align="left">
                                                            <?php echo $personalInformation->donar_member_id ?>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                    <tr class="table-td-info">
                                                        <td width="20%" align="left" class="content-table-td"><b>Member Type</b></td>
                                                        <td width="2%" align="center">:</td>
                                                        <td width="66%" align="left">
                                                            <?php echo $personalInformation->member_type_name ?>
                                                        </td>
                                                    </tr>
                                                    <tr class="table-td-info">
                                                        <td width="20%" align="left" class="content-table-td"><b>Member Status</b></td>
                                                        <td width="2%" align="center">:</td>
                                                        <td width="66%" align="left">
                                                            <?php 
                                                                if($personalInformation->is_active == 1){
                                                                    echo "Active";
                                                                }elseif($personalInformation->is_active == 0){
                                                                    echo "Inactive";
                                                                }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr class="table-td-info">
                                                        <td width="20%" align="left" class="content-table-td"><b>Mobile No</b></td>
                                                        <td width="2%" align="center">:</td>
                                                        <td width="66%" align="left">
                                                            <?php echo $personalInformation->primary_mobile ?>
                                                        </td>
                                                    </tr>
                                                    <tr class="table-td-info">
                                                        <td width="20%" align="left" class="content-table-td"><b>Email</b></td>
                                                        <td width="2%" align="center">:</td>
                                                        <td width="66%" align="left">
                                                            <?php echo $personalInformation->email ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                             
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        
                        
                        <!-- ------------ official information --------------- -->
                        <div class="div-separator">
                            Official Information
                        </div>
                        <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                            <tr class="table-td-info">
                                <td width="32%" align="left" class="content-table-td">Block</td>
                                <td width="2%" align="center">:</td>
                                <td width="66%" align="left">
                                    <?php echo $personalInformation->block_name ?>
                                </td>
                            </tr>

                            <tr class="table-td-info">
                                <td width="32%" align="left" class="content-table-td">Road</td>
                                <td width="2%" align="center">:</td>
                                <td width="66%" align="left">
                                    <?php echo $personalInformation->road_name ?>
                                </td>
                            </tr>
                            <tr class="table-td-info">
                                <td width="32%" align="left" class="content-table-td">House</td>
                                <td width="2%" align="center">:</td>
                                <td width="66%" align="left">
                                    <?php echo $personalInformation->society_plot ?>
                                </td>
                            </tr>
                            <tr class="table-td-info">
                                <td width="32%" align="left" class="content-table-td">Flat</td>
                                <td width="2%" align="center">:</td>
                                <td width="66%" align="left">
                                    <?php echo $personalInformation->society_flat ?>
                                </td>
                            </tr>
                            <tr class="table-td-info">
                                <td width="32%" align="left" class="content-table-td">Membership Joining Date</td>
                                <td width="2%" align="center">:</td>
                                <td width="66%" align="left">
                                    <?php
                                        if($personalInformation->first_joining_date != NULL && $personalInformation->first_joining_date != '0000-00-00'){
                                             echo $personalInformation->first_joining_date;
                                        }
                                    ?>
                                </td>
                            </tr>
                            
                            <tr class="table-td-info">
                                <td width="32%" align="left" class="content-table-td">Membership Proposed By </td>
                                <td width="2%" align="center">:</td>
                                <td width="66%" align="left">
                                    <?php echo $personalInformation->first_intro_member_name ?>			
                                </td>
                            </tr>
                            <tr class="table-td-info">
                                <td width="32%" align="left" class="content-table-td">Membership Seconded By </td>
                                <td width="2%" align="center">:</td>
                                <td width="66%" align="left">
                                    <?php echo $personalInformation->second_intro_member_name ?>			
                                </td>
                            </tr>
                            <tr class="table-td-info">
                                <td width="32%" align="left" class="content-table-td">System User </td>
                                <td width="2%" align="center">:</td>
                                <td width="66%" align="left">
                                    <?php 
                                        if($personalInformation->system_user == 1){
                                            echo "Yes";
                                        }elseif($personalInformation->system_user == 0){
                                            echo "No";
                                        }
                                    ?>			
                                </td>
                            </tr>
                            
                            <tr class="table-td-info">
                                <td width="32%" align="left" class="content-table-td">System User Group </td>
                                <td width="2%" align="center">:</td>
                                <td width="66%" align="left">
                                    <?php echo $personalInformation->group_name ?>			
                                </td>
                            </tr>
                            
                        </table>
                        
                        <!-- -------------- end official information ----------- -->
                        
                        <!-- ------------- Personal Information --------------- -->
                        <div class="div-separator">
                            Personal Information
                        </div>
                        <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                            <tr class="table-td-info">
                                <td width="20%" align="left" class="content-table-td">National ID</td>
                                <td width="2%" align="center">:</td>
                                <td width="30%" align="left">
                                    <?php echo $personalInformation->national_id ?>
                                </td>
                                
                                <td align="left" class="content-table-td">Religion</td>
                                <td width="2%" align="center">:</td>
                                <td align="left">
                                    <?php echo $personalInformation->religion ?>
                                </td>
                               
                                
                            </tr>
                            
                            <tr class="table-td-info">
                                <td align="left" class="content-table-td">Date of Birth</td>
                                <td width="2%" align="center">:</td>
                                <td  align="left">
                                    @if(!empty($personalInformation->dob))
                                        @php
                                            $dob = \Carbon\Carbon::parse($personalInformation->dob);
                                        @endphp

                                        {{ $dob->format('d-m-Y') }}
                                        (<b>Age:</b> {{ $dob->age }} years)
                                    @endif
                                </td>
                                 <td align="left" class="content-table-td">Occupation</td>
                                <td width="2%" align="center">:</td>
                                <td align="left">
                                    <?php echo $personalInformation->member_occupation_name ?>
                                </td>
                                
                                
                            </tr>
                            <tr class="table-td-info">
                                <td  align="left" class="content-table-td">Gender</td>
                                <td  width="2%" align="center">:</td>
                                <td  align="left">
                                    <?php echo ucfirst($personalInformation->gender) ?>
                                </td>
                                
                                <td align="left" class="content-table-td">Institution Name</td>
                                <td width="2%" align="center">:</td>
                                <td align="left">
                                    <?php echo $personalInformation->institution_name ?>
                                </td>
                                
                                
                            </tr>
                            
                            <tr class="table-td-info">
                                 <td width="22%" align="left" class="content-table-td">Nationality</td>
                                <td width="2%" align="center">:</td>
                                <td width="32%" align="left">
                                    <?php echo $personalInformation->nationality ?>
                                </td>
                                
                                <td align="left" class="content-table-td">Passport No</td>
                                <td width="2%" align="center">:</td>
                                <td align="left">
                                    <?php echo $personalInformation->passport_no ?>
                                </td>
                                
                                
                            </tr>
                            
                            <tr class="table-td-info">
                                 <td align="left" class="content-table-td">Blood Group</td>
                                <td width="2%" align="center">:</td>
                                <td align="left">
                                    <?php echo $personalInformation->blood_group ?>
                                </td>
                                
                                <td align="left" class="content-table-td">Passport Expiry Date</td>
                                <td width="2%" align="center">:</td>
                                <td align="left">
                                    {{ optional($personalInformation->passport_expiry_date)->format('d-m-Y') }}
                                </td>
                                
                               
                                
                            </tr>
                            <tr class="table-td-info">
                                <td align="left" class="content-table-td">Marital Status</td>
                                <td width="2%" align="center">:</td>
                                <td align="left">
                                    <?php 
                                        $spouseFlag = 0;
                                        if($personalInformation->marital_status == "Single"){
                                            $spouseFlag = 1;
                                        }
                                    echo $personalInformation->marital_status ?>
                                </td>
                                
                                <td align="left" class="content-table-td">Driving License No</td>
                                <td width="2%" align="center">:</td>
                                <td align="left">
                                    <?php echo $personalInformation->driving_license_no ?>
                                </td>
                            </tr>
                            <tr class="table-td-info">
                                <td align="left" class="content-table-td">Anniversary Date</td>
                                <td width="2%" align="center">:</td>
                                <td align="left">
                                    <?php 
                                        if($personalInformation->anniversary != NULL && $personalInformation->anniversary != '0000-00-00'){
                                            echo $personalInformation->anniversary;
                                        }
                                    ?>
                                </td>
                                <td align="left" class="content-table-td">Driving License Expiry Date</td>
                                <td width="2%" align="center">:</td>
                                <td align="left">
                                    <?php echo $personalInformation->driving_license_expiry_date ?>
                                </td>
                            </tr>
                        </table>
                        <!-- ---------- end personal information ------------------- -->
                        
                        <!-- ------------- Personal Contact Information --------------- -->
                        <div class="div-separator">
                            Personal Contact Information
                        </div>
                        <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                            <tr class="table-td-info">
                                <td width="32%" align="left" class="content-table-td">Secondary Mobile No</td>
                                <td width="2%" align="center">:</td>
                                <td width="66%" align="left">
                                    <?php echo $personalInformation->secendary_mobile ?>
                                </td>
                            </tr>
                            
                            <tr class="table-td-info">
                                <td  align="left" class="content-table-td">Land Phone</td>
                                <td  width="2%" align="center">:</td>
                                <td  align="left">
                                    <?php echo $personalInformation->member_tnt_phone ?>
                                </td>
                            </tr>
                            <tr class="table-td-info">
                                <td align="left" class="content-table-td">Present Address</td>
                                <td width="2%" align="center">:</td>
                                <td  align="left">
                                    <?php echo $personalInformation->present_address ?>
                                </td>
                            </tr>
                            
                            <tr class="table-td-info">
                                <td align="left" class="content-table-td">Permanent Address</td>
                                <td width="2%" align="center">:</td>
                                <td align="left">
                                    <?php echo $personalInformation->member_permanent_address ?>
                                </td>
                            </tr>
                        </table>

                        <!-- ------------- Parent Information --------------- -->
                        <div class="div-separator">
                            Parent Information
                        </div>
                        <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                            <tr class="table-td-info">
                                <td width="20%" align="left" class="content-table-td">Father's Name</td>
                                <td width="2%" align="center">:</td>
                                <td width="30%" align="left">
                                    <?php echo $personalInformation->father_name ?>
                                </td>
                                
                                <td align="left"  width="22%" class="content-table-td">Mother's Name</td>
                                <td width="2%" align="center">:</td>
                                <td  align="left">
                                    <?php echo $personalInformation->mother_name ?>
                                </td>
                                
                                
                            </tr>
                             <tr class="table-td-info">
                                
                                <td align="left" class="content-table-td">Father's Contact No</td>
                                <td width="2%" align="center">:</td>
                                <td align="left">
                                    <?php echo $personalInformation->father_contact ?>
                                </td>
                                
                                 <td align="left" class="content-table-td">Mother's Contact No</td>
                                <td width="2%" align="center">:</td>
                                <td align="left">
                                    <?php echo $personalInformation->mother_contact ?>
                                </td>
                                
                            </tr>
                            
                            <tr class="table-td-info">
                                <td width="22%" align="left" class="content-table-td">Father's Occupation</td>
                                <td width="2%" align="center">:</td>
                                <td width="24%" align="left">
                                    <?php echo $personalInformation->father_occupation_name ?>
                                </td>
                                
                                <td align="left" class="content-table-td">Mother's Occupation</td>
                                <td width="2%" align="center">:</td>
                                <td align="left">
                                    <?php echo $personalInformation->mother_occupation_name ?>
                                </td>
                            </tr>
                           
                            
                            <tr class="table-td-info">
                               
                                 <td  align="left" class="content-table-td">Father's Office Address</td>
                                <td  align="center">:</td>
                                <td  align="left">
                                    <?php echo $personalInformation->father_office_address ?>
                                </td>
                                <td align="left" class="content-table-td">Mother's Office Address</td>
                                <td width="2%" align="center">:</td>
                                <td align="left">
                                    <?php echo $personalInformation->mother_office_address ?>
                                </td>
                            </tr>
                        </table>
                        <!-- ---------- end parent information ------------------- -->

                        <?php if($spouseFlag != 1){?>
                        <div class="div-separator">
                            Spouse Information
                        </div>
                        <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                            <tr class="table-td-info">
                                <td width="20%" align="left" class="content-table-td">Spouse Name</td>
                                <td width="2%" align="center">:</td>
                                <td width="30%" align="left">
                                    <?php echo $personalInformation->spouse_name ?>
                                </td>
                                
                                <td width="22%" align="left" class="content-table-td">Spouse Occupation</td>
                                <td width="2%" align="center">:</td>
                                <td width="24%" align="left">
                                    <?php echo $personalInformation->spouse_occupation_name ?>
                                </td>
                            </tr>
                            
                            <tr class="table-td-info">
                                <td  align="left" class="content-table-td">Spouse Office Address</td>
                                <td  align="center">:</td>
                                <td  align="left">
                                    <?php echo $personalInformation->spouse_office_address ?>
                                </td>
                                
                                <td align="left" class="content-table-td">Spouse Contact No</td>
                                <td width="2%"align="center">:</td>
                                <td align="left">
                                    <?php echo $personalInformation->spouse_contact ?>
                                </td>
                            </tr>
                        </table>
                        <?php } ?>
                        <!-- ---------- end Spouse information ------------------- -->

                         <!-- ----------------- Other family member ----------- -->
                        <?php 
                            $otherFamilyMemberFlag = 0;
                            foreach($otherFamilyMembers as $otherFamilyMember){
                                if($personalInformation->id == $otherFamilyMember->member_id){
                                    $otherFamilyMemberFlag = 1;
                                    break;
                                }
                            }
                           
                            if($otherFamilyMemberFlag == 1){
                        ?>
                        
                        <div class="div-separator">
                            Other Family Member
                        </div>
                        <?php 
                            $serial = 1;
                            $totalYearExp = 0;
                            foreach ($otherFamilyMembers as $otherFamilyMember){
                                //$personalInformation $member_id hobe
                                if($otherFamilyMember->member_id == $personalInformation->id){?>
                        <div class="working-exp">
                            <?php 
                                echo "<b>".$serial.". ".$otherFamilyMember->relation_name."</b>";
                            ?> 
                        </div>
                        <div class="working-exp-table">
                            <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="margin-top:5px">
                                <tr class="table-td-info">
                                    <td width="8%" align="left" class="content-table-td">Name</td>
                                    <td width="2%" align="center">:</td>
                                    <td width="18.5%" align="left">
                                        <?php echo $otherFamilyMember->name ?>
                                    </td>

                                    <td width="10%" align="left" class="content-table-td">Date of Birth</td>
                                    <td width="2%" align="center">:</td>
                                    <td width="20%" align="left">
                                        @if(!empty($otherFamilyMember->dob))
                                            @php
                                                $dob = \Carbon\Carbon::parse($otherFamilyMember->dob);
                                            @endphp

                                            {{ $dob->format('d-m-Y') }}
                                            (<b>Age:</b> {{ $dob->age }} years)
                                        @endif
                                    </td>
                                </tr>

                                <tr class="table-td-info">
                                    <td width="" align="left" class="content-table-td">Mobile</td>
                                    <td width="" align="center">:</td>
                                    <td width="" align="left">
                                        <?php echo $otherFamilyMember->mobile ?>
                                    </td>

                                    <td width="" align="left" class="content-table-td">Email</td>
                                    <td width="" align="center">:</td>
                                    <td width="" align="left">
                                        <?php   
                                            echo $otherFamilyMember->email;
                                        ?>
                                    </td>
                                </tr>

                                <tr class="table-td-info">
                                    <td width="" align="left" class="content-table-td">Gender</td>
                                    <td width="" align="center">:</td>
                                    <td width="" align="left">
                                        <?php echo ucfirst($otherFamilyMember->gender) ?>
                                    </td>

                                    <td width="" align="left" class="content-table-td">Occupation</td>
                                    <td width="" align="center">:</td>
                                    <td width="" align="left">
                                        <?php   
                                            echo $otherFamilyMember->occupation_name;
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php 
                                $serial++;
                                } // if end
                            } // other family for loop end
                            
                            ?>
                       
                        <?php
                            }
                        ?>
                        <!-- ------------- end family member --------------- -->

                          <!-- ------------- Emergency  Information --------------- -->
                        <div class="div-separator">
                            Emergency Contact Information
                        </div>
                        <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
                            <tr class="table-td-info">
                                <td width="20%" align="left" class="content-table-td">Emergency Contact Name</td>
                                <td width="2%" align="center">:</td>
                                <td width="30%" align="left">
                                    <?php echo $personalInformation->emer_contact_name ?>
                                </td>
                                
                                <td width="22%" align="left" class="content-table-td">Emergency Contact No</td>
                                <td width="2%" align="center">:</td>
                                <td width="24%" align="left">
                                    {{ $personalInformation->emer_contact_mobile }}
                                </td>
                            </tr>
                            
                            <tr class="table-td-info">
                                <td  align="left" class="content-table-td">Relationship</td>
                                <td  align="center">:</td>
                                <td  align="left">
                                    <?php echo $personalInformation->emer_contact_relation ?>
                                </td>
                                
                                <td align="left" class="content-table-td">Address</td>
                                <td width="2%" align="center">:</td>
                                <td align="left">
                                    <?php echo $personalInformation->emer_contact_address ?>
                                </td>
                            </tr>
                        </table>
                        <br>
                        <!-- ---------- end emergency information ------------------- -->

                         <!-- --------------- education qualification ----------- -->
                        <?php 
                            $eduFlag = 0;
                            foreach($educationQualifications as $educationQualification){
                                //personalInformation member_id hobe
                                if($educationQualification->member_id == $personalInformation->id){
                                    $eduFlag = 1;
                                    break;
                                }
                            }
                            $EDUCATION_QUA_PERMISSION = false;
                            //&& EDUCATION_QUA_PERMISSION == 1 dekhte hobe
                            if($eduFlag == 1 && $EDUCATION_QUA_PERMISSION == 1){
                        ?>
                        
                        <div class="div-separator">
                            Education Qualification
                        </div>
                        
<!--                        <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="border:1px solid #666666">-->
                        <table class="education-qualification" width="100%"  align="center"  valign="middle" style="outline: 1.5px solid black;">
                            <tr>
                                <th style="width:100px">
                                    Exam Title
                                </th>
                                <th style="width:80px">
                                    Group
                                </th>
                                <th style="width:100px">
                                    Institute
                                </th>
                                <th style="width:140px">
                                    Result
                                </th>
                                <th style="width:85px">
                                    Passing Year
                                </th>
                                <th style="width:60px">
                                    Duration
                                </th>
                            </tr>
                            <?php
                                foreach($educationQualifications as $educationQualification){
                                    //$personalInformation->member_id
                                    if($educationQualification->member_id == $personalInformation->id){
                                        echo "<tr>";
                                            if($educationQualification->level_of_education == 'psc_5_pass' || $educationQualification->level_of_education == 'jsc_jdc_8_pass' || $educationQualification->level_of_education == 'secondary' || $educationQualification->level_of_education == 'higher_secondary'){
                                                //echo "<td>".$educationQualification->exam_title."</td>";
                                            }else{
                                                echo "<td>".$educationQualification->exam_degree."</td>";
                                            }
                                            
                                            if($educationQualification->major_group != ''){
                                                echo "<td>".$educationQualification->major_group."</td>";
                                            }else{
                                                echo "<td>-</td>";
                                            }
                                            
                                            echo "<td>".$educationQualification->institute_name."</td>";
                                            echo "<td>";
                                            if($educationQualification->qualification_result == 'grade'){
                                                echo "CGPA: ".$educationQualification->cgpa_marks." <br>out of ".$educationQualification->scale;
                                            }elseif($educationQualification->qualification_result == 'first_division_class' || $educationQualification->qualification_result == 'second_division_class' || $educationQualification->qualification_result == 'third_division_class'){
                                                echo $educationQualification->quali_result_name.', <br>Marks '.$educationQualification->cgpa_marks." <br>out of ".$educationQualification->scale;
                                            }else{
                                                echo $educationQualification->quali_result_name.', <br>CGPA/Marks :'.$educationQualification->cgpa_marks." <br>out of ".$educationQualification->scale; 
                                            }
                                            echo "</td>";
                                            echo "<td>".$educationQualification->passing_year."</td>";
                                            echo "<td>".$educationQualification->duration."</td>";
                                        echo "</tr>";
                                    }
                                }
                            
                            ?>
                        </table>
                        
                        <?php } ?>
                        <!-- ----------------- end education qualification ----------- -->

                        <!-- ----------------- Working Experience ----------- -->
                        <?php 
                            $workingFlag = 0;
                            foreach($workingExperiences as $workingExperience){
                                //$personalInformation->member_id hobe
                                if($workingExperience->member_id == $personalInformation->id){
                                    $workingFlag = 1;
                                    break;
                                }
                            }
                            $WORKING_EXP_PERMISSION = false;
                            //&& WORKING_EXP_PERMISSION == 1 dekhte hobe
                            if($workingFlag == 1 && $WORKING_EXP_PERMISSION == 1){
                        ?>
                        
                        <div class="div-separator">
                            Working Experience
                        </div>
                        <?php 
                            $serial = 1;
                            $totalYearExp = 0;
                            foreach ($workingExperiences as $workingExperience){
                                //$personalInformation->member_id hobe
                                if($workingExperience->member_id == $personalInformation->id){?>
                        <div class="working-exp">
                            <?php 
                                $toDate = "";
                                $toDayDate = "";
                                if($workingExperience->is_continued == 1){
                                    $toDate = 'Continuing';
                                    $toDayDate = date('Y-m-d');
                                }else{
                                    $toDayDate = $workingExperience->to_date;
                                    $toDate = \Carbon\Carbon::parse($workingExperience->to_date)->format('d-m-Y');
                                }
                                echo "<b>".$serial.". ".$workingExperience->designation."</b>
                                <span style='font-size:11px'>(".
                                (
                                    $workingExperience->from_date
                                        ? \Carbon\Carbon::parse($workingExperience->from_date)->format('d-m-Y')
                                        : ''
                                )
                                ." to ".
                                (
                                    $toDate
                                        ? \Carbon\Carbon::parse($toDate)->format('d-m-Y')
                                        : ''
                                )
                                .")</span>";
                                
                                ?> 
                        </div>
                        <div class="working-exp-table">
                            <b><?php echo $workingExperience->institution_name?></b>
                            <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="margin-top:5px">
                                <tr class="table-td-info">
                                    <td width="32%" align="left" class="content-table-td">Institution/Organization Type</td>
                                    <td width="2%" align="center">:</td>
                                    <td width="66%" align="left">
                                        <?php echo $workingExperience->institution_type ?>
                                    </td>
                                </tr>

                                <tr class="table-td-info">
                                    <td width="32%" align="left" class="content-table-td">Address</td>
                                    <td width="2%" align="center">:</td>
                                    <td width="66%" align="left">
                                        <?php echo $workingExperience->address ?>
                                    </td>
                                </tr>
                                <tr class="table-td-info">
                                    <td width="32%" align="left" class="content-table-td">Department</td>
                                    <td width="2%" align="center">:</td>
                                    <td width="66%" align="left">
                                        <?php echo $workingExperience->department ?>
                                    </td>
                                </tr>
                                <tr class="table-td-info">
                                    <td width="32%" align="left" class="content-table-td">Responsibilities</td>
                                    <td width="2%" align="center">:</td>
                                    <td width="66%" align="left">
                                        <?php
                                        echo $workingExperience->responsibilites;
                                        ?>   

                                    </td>
                                </tr>
                                <tr class="table-td-info">
                                    <td width="32%" align="left" class="content-table-td">Year of experience</td>
                                    <td width="2%" align="center">:</td>
                                    <td width="66%" align="left">
                                        @php
                                            $from = \Carbon\Carbon::parse($workingExperience->from_date);
                                            $to   = \Carbon\Carbon::parse($toDayDate);

                                            $diff = $from->diff($to);

                                            $years  = $diff->y;
                                            $months = $diff->m;

                                            // If you are summing total years only
                                            $totalYearExp = ($totalYearExp ?? 0) + $years;
                                        @endphp

                                        {{ $years }} years {{ $months }} month
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php 
                                $serial++;
                                } // if end
                            } //working exp for loop end
                            ?>
                        <div class="working-exp">
                            <br>
                            <b>Total year of experience: <?php echo $totalYearExp?></b> 
                        </div>
                        <?php
                        } // working flag (if working experince is in table
                        ?>
                        <!-- ------------- end working expwrience --------------- -->
                         <hr>
                        <div class="generated-footer">
                            <div class="left_signature">
                            Generated By:<b> {{ auth()->user()->fullName }} ({{ auth()->user()->username }})</b>
                            </div>
                            <div class="right_signature">
                            Generated Date & Time: <b><?php echo date("F j, Y, g:i a"); ;?></b>
                            </div>
                        </div>
                        <div class="footer">
                            <br>Developed By <b>ArrowLink™ Soft</b>
                        </div>

                    </div>
                    <br>
                    <div class="newPageDivClass"></div>
                <?php } ?>

            </div>  
        </div>  
    </div>

</body>
</html>