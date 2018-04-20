<?
$ob = $this->get("object");

$textareas = [ "use_effect" ];
?>
<div class="container">
    <div class="row">
        <h2>Edit Object: <?=$ob->get('name')?></h2>
        <form method="post" enctype="multipart/form-data">
            <?
            $vars = $ob->list_fields();
            foreach($vars as $k) {
                $name = "input_$k";
                ?>
                <div class="col-md-2">
                    <label for="<?=$name?>" class=""><?=ucfirst($k)?></label>
                </div>
                <div class="col-md-10">
                    <? if(in_array($k, $textareas)) { ?>
                        <textarea name="<?=$name?>" id="<?=$name?>" class="form-control"><?=$ob->get($k)?></textarea>
                    <? } else if($k == get_class($ob) . "_id") { ?>
                        <p><?=$ob->get($k)?></p>
                    <? } else { ?>
                        <input type="text" name="<?=$name?>" id="<?=$name?>" class="form-control" value="<?=$ob->get($k)?>">
                    <? } ?>
                    <br>
                </div>

                <?
            }
            ?>
            <input type="hidden" name="object_id" value="<?=$ob->get_idarr()[1]?>">
            <input type="hidden" name="class" value="<?=get_class($ob)?>">
            <button type="submit" class="btn btn-success">Save</button>
            <button type="button" class="btn btn-default cancelbutton">Done</button>
        </form>
    </div>
</div>
<script>
    $(function() {
        $(".cancelbutton").on("click", function() {
            window.location.href = "/<?=get_class($ob)?>_list";
        });
    });
</script>
