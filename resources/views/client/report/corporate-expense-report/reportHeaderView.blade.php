<style>
    .company-logo-outer-div{ 
        width: 100%;
        margin: 10px auto;
        position: relative;
    }
    .company-logo-inner-div{
        height:80px;
        width:250px;
        display:inline-block;
    }
    .company-logo-image{ 
        height:100%;
        object-fit:contain; 
        float: left;
    }
</style>
<?php $compamyInfo = get_company_info($data['company'] ?? $company); ?>
<div class="" style="width:726px">
    <div class="float-left" style="float:left;width: 40%;text-align: left">
        <div class="company-logo-outer-div">
            <div class="company-logo-inner-div">
				<?php
					if($compamyInfo[0]->profile_image){
						?>
                        <img
                            id="blah"
                            src="{{ asset('assets/images/corporate_company/' . $compamyInfo[0]->profile_image ?? null) }}"
                            alt="Logo"
                            class="company-logo-image"
                        > <?php
					}
				?>
                
            </div>
        </div>
    </div>
    <div class="float-right" style="float:right;width: 60%;text-align: right">
        <div class="heading-right-head">
            <?php echo $compamyInfo[0]->title ?>
        </div>
        <div class="heading-right-body">
            <?php
            $headInfo = ($compamyInfo[0]->address) ? $compamyInfo[0]->address . '<br>' : "";
            $headInfo .= ($compamyInfo[0]->company_mobile) ? $compamyInfo[0]->company_mobile . '<br>' : "";
            $headInfo .= ($compamyInfo[0]->company_email) ? $compamyInfo[0]->company_email : "";
            echo $headInfo;
            ?>
        </div>
    </div>
</div>
<div style="clear:both"></div>