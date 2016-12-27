@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col s12 white-text">
            <div class="card blue lighten-1">
                <div class="card-content">
                    <span class="card-title center-align">Préférences</span>
                    <table>
                        <tbody>
                            @foreach($preferences as $preference)
                                <td>
                                    <tr>{{ $preference->title }}</tr>
                                    <tr>
                                        @if(is_int($preference->value))
                                            <input type="number" id="{{ $preference->id }}" value="{{ $preference->value }}"/>
                                        @else
                                            <input type="text" id="{{ $preference->id }}" value="{{ $preference->value }}"/>
                                        @endif
                                    </tr>
                                </td>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @endsection

        @section('JS')

            <script>
                var token_id = "{{ Auth::user()->token_id }}";
                var token_key = "{{ Auth::user()->token_key}}";

                $(':input').change(function() {
                    var c = $(this);
                    $.when(
                        c.focusout()).then(function() {
                        console.log(c.attr('name') + ' = ' + c.val());
                        $.post({
                            url: '/api/v3/preferences/' + c.attr("id"),
                            headers: {
                                "Token-Id": token_id,
                                "Token-Key": token_key
                            },
                            data: {
                                value: c.val()
                            },
                            success: function(data, textStatus, xhr){
                                if(data['status'] == "success"){
                                    Materialize.toast(data['userInfo'], 4000);
                                }
                            }
                        })
                    });
                });
            </script>
@endsection
