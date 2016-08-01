@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col s12  white-text" id="Code">
            <div class="card grey">
                <div class="card-content">
                    <span class="card-title">Validation du code</span>
                    <input type="tel" id="code"/>
                    <a href="#" id="button">Valider</a>
                </div>
            </div>
        </div>
    </div>
        @endsection

        @section('JS')
            <script>
                $("#button").onclick(function(){
                    $.post({
                        url: "/api/v2/garage/up/",
                        headers: {
                            'Token-Id': this.props.tokenID,
                            'Token-Key': this.props.tokenKey
                        },
                        data: {
                            'code': $('#code').val()
                        },
                        success: function (data) {
                            console.log(JSON.parse(data))
                        }.bind(this),
                        error: function (xhr, status, err) {
                            console.error(this.props.url, status, err.toString());
                        }.bind(this)
                    });
                })
            </script>
@endsection
