@extends('layouts.scaffold')

@section('main')
<?php	

$items = DB::select(DB::raw(' select i.*, votescount from items i left join (select votes.item_id, count(1) as votescount from votes group by votes.item_id) v on i.id = v.item_id where active = 1 order by coalesce(v.votescount, 0) DESC '));

$queries = DB::getQueryLog();	
$last_query = end($queries);
var_dump($last_query);
 ?>
 
@stop