<?php
    class Category
    {
        private $name;
        private $id;

        function __construct($name, $id = null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        function getName()
        {
            return $this->name;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO categories (name) VALUES ('{$this->getName()}')");
            $this->id= $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_categories = $GLOBALS['DB']->query("SELECT * FROM categories;");
            // var_dump($returned_categories);
            $categories = array();
            foreach($returned_categories as $category) {
                $name = $category['name'];
                $id = $category['id'];
                $new_category = new Category($name, $id);
                array_push($categories, $new_category);
            }
            return $categories;
        }

        function getTasks()
        {
            $tasks = Array();
            $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks WHERE category_id = {$this->getId()} ORDER BY due_date;");
            foreach($returned_tasks as $task) {
                $description = $task['description'];
                $id = $task['id'];
                $category_id = $task['category_id'];
                $due_date = $task['due_date'];
                $new_task = new Task($description, $id, $category_id, $due_date);
                array_push($tasks, $new_task);
            }
            return $tasks;
        }

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM categories;");
        }

        static function find($search_id)
        {
            $found_category = null;
            $categories = Category::getAll();
            foreach($categories as $category) {
                $category_id = $category->getId();
                if ($category_id == $search_id) {
                  $found_category = $category;
                }
            }
            return $found_category;
        }
    }
?>
