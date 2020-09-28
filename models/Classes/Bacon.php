<?php
class Bacon
{
    // private $first;
    // private $second;
    private $storage;
    // private $counter;
    // private $used_person_ids;
    // private $used_series_ids;
    // private $depth;
    // private $connection;
    // private $connection_found;
    private $tree;
    private $used_persons;
    private $used_series;
    private $array = [];
    private $depth;

    public function __construct($storage)
    {
        $this->storage = $storage;
        $this->counter = 0;
        $this->used_person_ids = [];
        $this->used_series_ids = [];
        $this->depth = 0;
        $this->connection = [];
        $this->tree = [];
        $this->used_persons = [];
        $this->used_series = [];
        $this->array = [];
    }
    public function showForm()
    {
        echo '<form method="post">';
        echo '<select name="first">';
        $persons = $this->storage->getPersons();
        foreach ($persons as $person) {
            echo '<option value="' . $person->getID() . '">' . $person->getName() . '</option>';
        }
        echo '</select>';
        echo '<select name="second">';
        foreach ($persons as $person) {
            echo '<option value="' . $person->getID() . '">' . $person->getName() . '</option>';
        }
        echo '</select>';
        echo '<input type="submit" name="submit_bacon">';
        echo '</form>';
    }

    public function createArray($first)
    {
        $this->array[] = $first;
        $this->used_persons[] = $first;
        $persons_of_gen = [];
        $series = $this->storage->getSeriesOfActor($first);
        foreach ($series as $s) {
            if (!in_array($s->getID(), $this->used_series)) {
                $this->used_series[] = $s->getID();
                $persons = $this->storage->getActorsOfSeries($s->getID());
                foreach ($persons as $person) {
                    if (!in_array($person->getID(), $this->used_persons)) {
                        $this->used_persons[] = $person->getID();
                        $persons_of_gen[] = $person->getID();
                    }
                }
            }
        }
        $this->array[1] = $persons_of_gen;
        $this->newGeneration($this->array[1], $persons_of_gen);
        return $this->depth;
    }
    private function newGeneration(&$array, $persons)
    {
        $persons_of_gen = [];
        foreach ($persons as $person) {
            $series = $this->storage->getSeriesOfActor($person);
            foreach ($series as $s) {
                if (!in_array($s->getID(), $this->used_series)) {
                    $this->used_series[] = $s->getID();
                    $actors = $this->storage->getActorsOfSeries($s->getID());
                    foreach ($actors as $actor) {
                        if (!in_array($actor->getID(), $this->used_persons)) {
                            $this->used_persons[] = $actor->getID();
                            $persons_of_gen[] = $actor->getID();
                        }
                    }
                }
            }
        }
        if ($persons_of_gen) {
            $array[] = $persons_of_gen;
            $this->newGeneration($array[count($array) - 1], $persons_of_gen);
        } else {
            $this->depth = $this->getDepth($this->array, 0);
        }
    }

    private function getDepth($array, $depth)
    {
        $looking_for = $_POST['second'];
        foreach ($array as $value) {
            if (!is_array($value)) {
                if ($value == $looking_for) {
                    return $depth;
                }
            }
        }
        $depth++;
        return $this->getDepth($array[count($array) - 1], $depth);
        
    }

    // ----------------------------------------------------------------------------------------------

    // public function getGenerations($first)
    // {
    //     $this->tree = [];
    //     $this->tree[] = [$first];
    //     $this->used_persons[] = $first;
    //     $series_first = $this->storage->getSeriesOfActor($first);
    //     foreach ($series_first as $s_first) {
    //         $this->used_series[] = $s_first->getID();
    //         $actors = $this->storage->getActorsOfSeries($s_first->getID());
    //         $this->insertGenerations($this->tree, $actors);
    //     }
    //     var_dump(highlight_string("<?\n". var_export($this->tree, true)));

    // }
    // private function insertGenerations(&$tree, $actors)
    // {
    //     $index_counter = 0;
    //     $counter = 0;
    //     foreach ($actors as $actor) {
    //         if (!in_array($actor->getID(), $this->used_persons)) {
    //             $this->used_persons[] = $actor->getID();
    //             $tree[0][1][] = [$actor->getID()];
    //             $series = $this->storage->getSeriesOfActor($actor->getID());
    //             foreach ($series as $s) {
    //                 if (!in_array($s->getID(), $this->used_series)) {
    //                     $this->used_series[] = $s->getID();
    //                     $actors_new = $this->storage->getActorsOfSeries($s->getID());
    //                     // $this->insertGenerations($tree[$index_counter], $actors_new);
    //                     // var_dump(highlight_string("<?\n". var_export($tree[0][1][$counter - 1][0], true)));
    //                     $prev_actor = $tree[0][1][$counter - 1][0];
    //                     // echo $prev_actor;
    //                     $this->insert($tree[0][1][$counter - 1], $actors_new);
    //                     // $this->insert($tree[0][1], $actors_new);

    //                 }
    //             }
    //         }
    //         // $index_counter++;
    //         $counter++;

    //     }
    // }
    // private function insert(&$tree, $actors)
    // {
    //     $counter = 0;
    //     // var_dump(highlight_string("<?\n". var_export($tree, true)));
    //     foreach ($actors as $actor) {
    //         if (!in_array($actor->getID(), $this->used_persons)) {
    //             $this->used_persons[] = $actor->getID();
    //             $tree[1][] = [$actor->getID()];

    //             $series = $this->storage->getSeriesOfActor($actor->getID());
    //             foreach ($series as $s) {
    //                 if (!in_array($s->getID(), $this->used_series)) {
    //                     $this->used_series[] = $s->getID();
    //                     $actors_new = $this->storage->getActorsOfSeries($s->getID());
    //                     // $counter++;

    //                     // echo $s->getTitle();
    //                     // echo $actor->getName();

    //                     // var_dump(highlight_string("<?\n". var_export($tree[1][$counter][0], true)));
                        
    //                     // $this->insert($tree[1][$counter - 1], $actors_new);
    //                     echo $counter;
    //                     var_dump(highlight_string("<?\n". var_export($tree, true)));
    //                     $this->insert($tree[1][$counter], $actors_new);

    //                     // echo $counter;
    //                     $counter++;
    //                 }
    //             }
    //         }
    //         // $counter++;

    //     }

    // }

    // -----------------------------------------------------------------------------------------------------

    // public function getRelation($first, $second)
    // {
    //     if ($first == $second) {
    //         $this->connection = [$first];
    //         return $this->connection;
    //     } else {
    //         $series_first = $this->storage->getSeriesOfActor($first);
    //         $series_second = $this->storage->getSeriesOfActor($second);
    //         $this->used_person_ids[] = $first;
    //         foreach ($series_first as $series) {
    //             $this->compareSeries($first, $series, $series_second, $second);
    //         }
    //         if ($this->connection_found) {
    //             return $this->connection;
    //         } else {
    //             return 'Bacon Nummer nicht vorhanden';
    //         }        
    //     }
    // }

    // private function compareSeries($first, $series, $series_second, $second)
    // {
    //     if (in_array($series, $series_second)) {
    //         if (!in_array($first, $this->connection)) {
    //             array_unshift($this->connection, $first);
    //         }
    //         $this->connection[] = $second;
    //         $this->connection_found = true;
    //         return $this->connection;
    //     } else {
    //         $actors = $this->storage->getActorsOfSeries($series->getID());
    //         foreach ($actors as $actor) {
    //             if (!in_array($actor->getID(), $this->used_person_ids)) {
    //                 $this->used_person_ids[] = $actor->getID();
    //                 $this->connection[] = $actor->getID();
    //                 $this->depth++;
    //                 $series_new = $this->storage->getSeriesOfActor($actor->getID());
    //                 foreach ($series_new as $s_new) {
    //                     if (!in_array($s_new->getID(), $this->used_series_ids)) {
    //                         $this->used_series_ids[] = $s_new->getID();
    //                         $this->compareSeries($first, $s_new, $series_second, $second);
    //                         if ($this->connection_found) {
    //                             break;
    //                         }
    //                         for ($i = 0; $i < $this->depth; $i++) {
    //                             array_pop($this->connection);
    //                         }
    //                         $this->depth = 0;
    //                     }
    //                 }
    //             }
    //         }
    //     }
    // }

    // public function changeArray()
    // {
    //     print_r($this->connection);
    //     for ($i = 0; $i < count($this->connection) - 2; $i++) {
    //         $series_i = $this->storage->getSeriesOfActor($this->connection[$i]);
    //         $this->removePart($i, $series_i);
    //     }
    //     return $this->connection = array_values($this->connection);
    // }
    // public function removePart($index, $series_i)
    // {
    //     for ($j = 2; $j < count($this->connection) - 1; $j++) {
    //         $series_j = $this->storage->getSeriesOfActor($this->connection[$j]);
    //         $result = array_map(
    //             'unserialize',
    //             array_intersect(
    //                 array_map(
    //                     'serialize',
    //                     $series_i
    //                 ),
    //                 array_map(
    //                     'serialize',
    //                     $series_j
    //                 )
    //             )
    //         );
    //         if (empty($result)) {
    //             // return;
    //         } else {
    //             // unset($this->connection[$j]);
    //             array_splice($this->connection, $j - 1, 1);
    //         }
    //     }
    // }
}
