<?

if($this->get("okaction")) {
    $okaction = $this->get("okaction");
} else {
    $okaction = "/";
}

if($this->get("okmessage")) {
    $okmessage = $this->get("okmessage");
} else {
    $okmessage = "Return to landing page";
}
?>


<div class="errorpage-container">
    <span class="fa <?=$this->get("icon")?> bigicon"></span>
    <h2><?=$this->get("page_title")?></h2>

    <div>
        <?=html::get_page_messages()?>
        <a href="<?=$this->get("button_url")?>" class="btn btn-default btn-ok"><?=$this->get("button_title")?></a>
    </div>
</div>

<style>

    .bigicon {
        font-size: 97px;
        color: #f96145;
    }

    .errorpage-container {
        text-align: center;
        width: 600px;
        border-radius: 0.5rem;
        top: 50px;
        bottom: 0;
        left: 0;
        right: 0;
        margin: auto;
        background-color: white;
        padding: 20px;
        position: relative;
    }

    @media all and (max-width: 620px) {
        .errorpage-container {
            width: 320px;
        }
    }

</style>