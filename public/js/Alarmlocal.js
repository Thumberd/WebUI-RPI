"use strict";

var Alarmlocal = React.createClass({
  displayName: "Alarmlocal",

  getInitialState: function getInitialState() {
    return { etat: "", color: "", link: "" };
  },
  loadState: function loadState() {
    $.post({
      url: "/alarmState",
      data: { _token: this.props.token },
      success: function (data) {
        if (data.indexOf("0") > -1) {
          this.setState({ etat: "Désactivée", color: "green lighten-1", link: "Activer" });
        } else if (data.indexOf("1") > -1) {
          this.setState({ etat: "Activée", color: "red lighten-1", link: "Désactiver" });
        }
      }.bind(this),
      error: function (xhr, status, err) {
        console.error(this.props.url, status, err.toString());
      }.bind(this)
    });
  },
  componentDidMount: function componentDidMount() {
    this.loadState();
    setInterval(this.loadState, 10000);
  },
  render: function render() {
    return React.createElement("div", { "className": "card ".concat(this.state.color) }, React.createElement("div", { "className": "card-content white-text" }, React.createElement("span", { "className": "card-title" }, "Alarm"), React.createElement("br", null), "Etat: ", this.state.etat));
  }
});