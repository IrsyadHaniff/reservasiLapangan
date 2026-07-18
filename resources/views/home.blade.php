@extends('layouts.app')

@section('title', 'SM-SPORT CENTER - beranda')

@section('content')

<x-hero />

<x-reservasi :lapangans="$lapangans" />

<x-about />

@endsection