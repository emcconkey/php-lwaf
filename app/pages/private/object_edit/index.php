<?
class object_edit extends page {
    function get_page($args) {
        $type = $args[0];
        $id = $args[1];
        $object = new $type();
        $object->load($id);
        if(!$object->loaded() && $id != "new") {
            echo "Invalid object id";
            return;
        } else if($id == "new") {
            $object = new $type();
            $object->set("name", "New Object");
            $object->set($type . "_id", "new_object");
        }
        $this->set("object", $object);

        $this->set("page_title", "Edit Object");

        $this->render("header");
        $this->render();
        $this->render("footer");

    }

    function post_page($args) {

        $object_class = $_POST['class'];
        $object_id = $_POST['object_id'];
        $ob = new $object_class();

        if($_POST['object_id'] != "new_object") {
            $ob->load($object_id);
            if(!$ob->loaded()) {
                echo "Invalid object id";
                return;
            }
        }

        foreach($_POST as $k => $v) {
            if(substr($k, 0, 6) == "input_") {
                $key = str_replace("input_", "", $k);
                $ob->set($key, $v);
            }
        }
        $ob->save();
        $object_id = $ob->get($object_class . "_id");

        header("Location: /object_edit/$object_class/$object_id");
    }
}