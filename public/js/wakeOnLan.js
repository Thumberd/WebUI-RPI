"use strict";

var wakeOnLan = React.createClass({
  displayName: "wakeOnLan",

  getInitialState: function getInitialState() {
    return { color: "teal", text: "Power On" };
  },
  powerOn: function powerOn(e) {
    $.ajax({
      url: "/api/v2/wakeonlan",
      method: "POST",
      data: { id: this.props.id },
      headers: {
        'Token-Id': this.props.tokenID,
        'Token-Key': this.props.tokenKey
      },
      success: function (data) {
        console.log(data);
        Materialize.toast('Device is up', 2000);
        this.setState({ color: "teal darken-3", text: "UP" });
      }.bind(this),
      error: function (xhr, status, err) {
        console.error(this.props.url, status, err.toString());
        this.setState({ color: "red darken-3", text: "ERREUR API" });
      }.bind(this)
    });
  },
  render: function render() {
    return React.createElement("a", { "className": "waves-effect waves-orange btn " + this.state.color, onClick: this.powerOn }, React.createElement("i", { "className": "fa fa-power-off left" }), this.state.text);
  }
});
