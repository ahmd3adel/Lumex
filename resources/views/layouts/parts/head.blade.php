<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
{{--    <title>{{config('app.name')}} | @yield('title')</title>--}}
    <title>{{trans('Home')}} | @yield('title')</title>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">

    <!-- Google Font: Source Sans Pro -->
{{--    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">--}}
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
{{--    <link--}}
{{--        rel="stylesheet"--}}
{{--        href="https://cdn.rtlcss.com/bootstrap/v4.5.3/css/bootstrap.min.css"--}}
{{--        integrity="sha384-JvExCACAZcHNJEc7156QaHXTnQL3hQBixvj5RV5buE7vgnNEzzskDtx9NQ4p6BJe"--}}
{{--        crossorigin="anonymous" />--}}
    <!-- Load Bootstrap RTL theme from RawGit -->
    <link rel="stylesheet" href="{{asset('dist/css/custom.css')}}">

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
