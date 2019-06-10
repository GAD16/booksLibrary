<?php

class books_model extends CI_Model {

    public function __construct()    {

        $this->load->database();
    }

   public function get_authors ($id = false)   {

        if ($id === false)    {

            $query = $this->db->get('authors');
            return $query->result_array();
        }

       $query = $this->db->get_where('authors', array('id' => $id));
       return $query->row_array();
   }

   public function get_lib() {
        //Выбирает жанр(через запятую если их несколько), автор, название, год
       $query = $this->db->query('
            SELECT GROUP_CONCAT(genres.genre) as genre, full_name AS author, name, year
            FROM books
            JOIN book_genres ON book_genres.book_id = books.id
            JOIN genres ON genres.genre_id = book_genres.genre_id
            JOIN book_authors ON book_authors.book_id = books.id
            JOIN authors ON authors.author_id = book_authors.author_id
            GROUP BY books.id');

       return $query->result_array();

    }

    public function add_book ($genre, $author, $book, $year) {


        if (!is_set($genre) || !is_set($author) || !is_set($book) || !is_set($year)) {
            return "не все поля заполнены";
        }

        if (!is_string($genre) || !is_string($author) || !is_string($book) || !is_int($year)) {
            return "не верный формат введенных данных";
        }

        if (strlen($year)<>4){
            return "не верный формат года";
        }

        if ($year>date('Y')) {
            return "год написания книги не может быть больше текущего";
        }
        $book = preg_replace('/[^а-яёa-z\s]/iu','', $book);
        $book = strtolower($book);
        $book = ucwords($book);
        if ($book)

        $data = array(
            'name' => $name,
            'year' => $year,
            );

        $this->db->insert('books', $data);
        $this->db->set('full_name',$author);
        $this->db->insert('authors');
        $this->db->set('genre',$genre);
        $this->db->insert('genres');

        $this->db->select('id');
        $this->db->from('books');
        $this->db->where('name',$book);
        $book_id = $this->db->get();

        if



    }
//функция приводит строку к виду ФИО. То есть делит на отдельные слова, удаляет знаки припинания и пробелы,
//каждое новое слово пишет с большой буквы.
    public function normal_name($n){
        $n = preg_replace('/[^а-яёa-z\s]/iu', ' ', $n);

        $n = mb_strtolower($n, 'UTF-8');


        for ($i = 0, $arr = [], $size = strlen($n); $i < $size; $i++) {
            $b = mb_substr($n, $i, 1, 'UTF-8');
            if ($b == '') {
            } else {
                array_push($arr, $b);
            }
        }


        $uper = true;
        $del = true;
        $result = [];
        foreach ($arr as $liter) {
            if ($liter == " ") {
                $uper = true;
                if ($del == false) {
                    $del = true;
                    array_push($result, $liter);
                    //var_dump("пробел найден но не удален" . '"' . $liter . '"');
                }
                //var_dump("пробел найден И удален" . '"' . $liter . '"');
            } else {
                $del = false;
                if ($uper == true) {
                    $uper = false;
                    array_push($result, mb_strtoupper($liter, 'UTF-8'));
                    //var_dump("Буква сделана большой и помещена в моссив" . '"' . $liter . '"');
                } else {
                    array_push($result, $liter);
                    //var_dump("просто помещена в массив как есть" . '"' . $liter . '"');
                }
            }
        }

        $size = count($result);
        $i = 1;
        if ($result[$size - 1] == ' ') {
            array_pop($result);
            //var_dump('выполняется c пробелом в конце строки');
            foreach ($result as $liter) {

                $string = $string . $liter;

            }
        } else {
            //var_dump('выполняется без пробелоа  в конце строки');
            foreach ($result as $liter) {

                $string = $string . $liter;
            }
        }
        // $string = preg_replace('/[^а-яёa-z\s]/iu', '', $string);
        return $string;

    }
}