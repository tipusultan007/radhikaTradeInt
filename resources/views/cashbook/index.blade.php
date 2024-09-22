@extends('layouts.app')

@section('title','Cashbook')
@section('content')

@endsection
@section('js')
    <script>
        $(".category,#account_id").select2({
            dropdownParent: $("#expenseModal"),
            theme: "bootstrap",
            width: "100%",
            placeholder: '--Select Category--'
        });
        $("#expense_category_id").select2({
            theme: "bootstrap",
            width: "100%",
            placeholder: '--Select Category--'
        });
    </script>
@endsection
