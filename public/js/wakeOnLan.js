"use strict";

var wakeOnLan = React.createClass({
  displayName: "wakeOnLan",

  getInitialState: function getInitialState() {
    return { color: "orange lighten-3", text: "Power On" };
  },
  powerOn: function powerOn(e) {
    $.ajax({
      url: "/api/v1/wakeOnLan",
      method: "POST",
      data: { id: this.props.id, _token: this.props.token },
      success: function (data) {
        console.log(data);
        Materialize.toast('Device is up', 2000);
        this.setState({ color: "green lighten-1", text: "UP" });
      }.bind(this),
      error: function (xhr, status, err) {
        console.error(this.props.url, status, err.toString());
        Materialize.toast(err.toString(), 4000);
      }.bind(this)
    });
  },
  render: function render() {
    return React.createElement("a", { "className": "waves-effect waves-orange btn " + this.state.color, onClick: this.powerOn }, React.createElement("i", { "className": "fa fa-power-off left" }), this.state.text);
  }
});