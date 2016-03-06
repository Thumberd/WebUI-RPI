"use strict";

var AlarmBox = React.createClass({
  displayName: "AlarmBox",

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
  handleClick: function handleClick(e) {
    e.preventDefault();
    var stateWill;
    if (this.state.etat == "Désactivée") {
      stateWill = 1;
    } else if (this.state.etat == "Activée") {
      stateWill = 0;
    }
    $.post({
      url: "/alarmStateUp",
      data: { state: stateWill, _token: this.props.token },
      success: function (data) {
        console.log(data);
        Materialize.toast('Request in progress', 2000);
      }.bind(this),
      error: function (xhr, status, err) {
        console.error(this.props.url, status, err.toString());
        Materialize.toast('An error occured', 4000);
      }.bind(this)
    });
  },
  componentDidMount: function componentDidMount() {
    this.loadState();
    setInterval(this.loadState, 10000);
  },
  render: function render() {
    return React.createElement("div", { "className": "card ".concat(this.state.color) }, React.createElement("div", { "className": "card-content white-text" }, React.createElement("span", { "className": "card-title" }, "Alarm"), React.createElement("br", null), "Etat: ", this.state.etat), React.createElement("div", { "className": "card-action" }, React.createElement("a", { href: "#", onClick: this.handleClick }, this.state.link)));
  }
});