<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{trans('El-NOUR')}} | @yield('title')</title>

    <link rel="stylesheet" href="{{asset('assets/css/datatable.css')}}">
    
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">

    <link rel="stylesheet" href="{{asset('assets/css/rtl.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/custom.css')}}">
<link href="{{ asset('dist/css/sweerAlert2.css') }}" rel="stylesheet">

@stack('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
