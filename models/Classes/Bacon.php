<?php
class Bacon
{
    // private $first;
    // private $second;
    private $storage;
    private $counter;
    private $used_person_ids;
    private $used_series_ids;
    private $depth;
    private $connection;
    private $connection_found;
    public function __construct($storage)
    {
        $this->storage = $storage;
        $this->counter = 0;
        $this->used_person_ids = [];
        $this->used_series_ids = [];
        $this->depth = 0;
        $this->connection = [];
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

    public function getRelation($first, $second)
    {
        if ($first == $second) {
            $this->connection = [$first];
            return $this->connection;
        } else {
            $series_first = $this->storage->getSeriesOfActor($first);
            $series_second = $this->storage->getSeriesOfActor($second);
            $this->used_person_ids[] = $first;
            foreach ($series_first as $series) {
                $this->compareSeries($first, $series, $series_second, $second);
            }
            if ($this->connection_found) {
                return $this->connection;
            } else {
                return 'Bacon Nummer nicht vorhanden';
            }        
        }
    }

    private function compareSeries($first, $series, $series_second, $second)
    {
        if (in_array($series, $series_second)) {
            if (!in_array($first, $this->connection)) {
                // $this->connection[] = $first;
                array_unshift($this->connection, $first);
            }
            $this->connection[] = $second;
            // if successful return
            $this->connection_found = true;
            return $this->connection;
        } else {
            $actors = $this->storage->getActorsOfSeries($series->getID());
            foreach ($actors as $actor) {
                if (!in_array($actor->getID(), $this->used_person_ids)) {
                    $this->used_person_ids[] = $actor->getID();
                    $this->connection[] = $actor->getID();
                    $this->depth++;
                    $series_new = $this->storage->getSeriesOfActor($actor->getID());
                    foreach ($series_new as $s_new) {
                        if (!in_array($s_new->getID(), $this->used_series_ids)) {
                            $this->used_series_ids[] = $s_new->getID();
                            $this->compareSeries($first, $s_new, $series_second, $second);
                            for ($i = 0; $i < $this->depth; $i++) {
                                // array_pop($this->connection);
                            }
                            $this->depth = 0;
                        }
                    }
                }
            }
        }
    }

    

    // public function getRelation($first, $second)
    // {
    //     $this->connection[] = $first;
    //     if ($first == $second) {
    //         return $this->connection;
    //     } else {
    //         $series_first = $this->storage->getSeriesOfActor($first);
    //         $series_second = $this->storage->getSeriesOfActor($second);
    //         $result = array_map(
    //             'unserialize',
    //             array_intersect(
    //                 array_map(
    //                     'serialize',
    //                     $series_first
    //                 ),
    //                 array_map(
    //                     'serialize',
    //                     $series_second
    //                 )
    //             )
    //         );
    //         if (empty($result)) {
    //             foreach ($series_first as $series) {
    //                 if (!in_array($series->getID(), $this->used_series_ids)) {
    //                     $this->used_series_ids[] = $series->getID();
    //                     $actors = $this->storage->getActorsOfSeries($series->getID());
    //                     foreach ($actors as $actor) {
    //                         if (
    //                             !in_array($actor->getID(), $this->used_person_ids)
    //                             && $actor->getID() != $first
    //                         ) {
    //                             $this->used_person_ids[] = $actor->getID();
    //                             // print_r($this->getRelation($actor->getID(), $second));
    //                             // $a = $this->getRelation($actor->getID(), $second);
    //                             // print_r($a);
    //                             // echo 'a';
    //                             // return $a;
    //                             // $this->connection = $this->getRelation($actor->getID(), $second);
    //                             // print_r($this->connection);
    //                             // return $this->getRelation($actor->getID(), $second);
    //                             return $this->getRelation($actor->getID(), $second);
    //                         } else {

    //                         }
    //                     }
    //                 } else {

    //                 }
    //             }
    //             array_pop($this->connection);
    //         } else {
    //             $this->connection[] = $second;
    //             print_r($this->connection);
    //             return $this->connection;
    //         }
    //     }

    //     // if ($first == $second) {
    //     //     return $this->connection;
    //     // } else {
    //     //     if (!in_array($first, $this->used_person_ids)) {
    //     //         $this->used_person_ids[] = $first;
    //     //         $series_first = $this->storage->getSeriesOfActor($first);
    //     //         $series_second = $this->storage->getSeriesOfActor($second);
    //     //         $result = array_map(
    //     //             'unserialize',
    //     //             array_intersect(
    //     //                 array_map(
    //     //                     'serialize',
    //     //                     $series_first
    //     //                 ),
    //     //                 array_map(
    //     //                     'serialize',
    //     //                     $series_second
    //     //                 )
    //     //             )
    //     //         );
    //     //         if (empty($result)) {
    //     //             foreach ($series_first as $series) {
    //     //                 if (!in_array($series->getID(), $this->used_series_ids)) {
    //     //                     $this->used_series_ids[] = $series->getID();
    //     //                     $actors = $this->storage->getActorsOfSeries($series->getID());
    //     //                     foreach ($actors as $actor) {
    //     //                         $this->used_person_ids[] = $actor->getID();
    //     //                         print_r($this->getRelation($actor->getID(), $second));
    //     //                         // return $this->getRelation($actor->getID(), $second);
    //     //                         // $this->getRelation($actor->getID(), $second);

    //     //                         // $this->connection = $this->getRelation($actor->getID(), $second);
    //     //                         // print_r($this->getRelation($actor->getID(), $second));

    //     //                     }
    //     //                 }
    //     //             }
    //     //             array_pop($this->connection);
    //     //         } else {
    //     //             $this->connection[] = $second;
    //     //             return $this->connection;
    //     //         }
    //     //     } 
    //     // }
    // }
}
