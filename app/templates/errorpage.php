<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Error</title>
    <style>
        html{
        }
        body{
            margin: 0;
            padding: 0;
            background: #e7ecf0;
            font-family: Arial, Helvetica, sans-serif;
        }
        *{
            margin: 0;
            padding: 0;
        }
        p{
            font-size: 18px;
            color: #373737;
            font-family: Arial, Helvetica, sans-serif;
            line-height: 18px;
        }
        a{
            text-decoration: none;
            padding-top: 6px;
            padding-bottom: 6px;
            padding-left: 12px;
            padding-right: 12px;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            background-color: #2e6da4;
            color: white;
        }
        a:hover {
            background-color: #1a4f6f;
        }
        .f-left{
            float:left;
        }
        .f-right{
            float:right;
        }
        .clear{
            clear: both;
            overflow: hidden;
        }
        #block_error{
            width: 90%;
            max-width: 845px;
            height: 384px;
            border: 1px solid #cccccc;
            margin: 72px auto 0;
            -moz-border-radius: 4px;
            -webkit-border-radius: 4px;
            border-radius: 4px;
            background: #fff url(/static/img/block.gif) no-repeat 0 51px;
        }
        #block_error div{
            padding: 100px 40px 0 186px;
        }
        h1{
            color: #218bdc;
            font-size: 24px;
            display: block;
            padding: 0 0 14px 0;
            border-bottom: 1px solid #cccccc;
            margin-bottom: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body marginwidth="0" marginheight="0">
<div id="block_error">
    <div>

        <h1><?=$this->get("page_title")?></h1>
        <p>
            <?=$this->get("page_content")?>
        </p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>
            <? if($this->get("show_button")) { ?>
                <a href="/">Continue</a>
            <? } ?>
        </p>

    </div>
</div>
</body>
</html>