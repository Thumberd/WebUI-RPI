"use strict";

var GarageBox = React.createClass({
  displayName: "GarageBox",

  getInitialState: function getInitialState() {
    return { state: "" };
  },
  loadState: function loadState() {
    console.log(this.props.id);
    $.get({
      url: "/api/v1/garage/" + this.props.id,
      headers: {
        'Token-Id': this.props.tokenID,
        'Token-Key': this.props.tokenKey
      },
      success: function (data) {
	console.log(JSON.parse(data))
        this.setState({ state: JSON.parse(data)['state'] });
      }.bind(this),
      error: function (xhr, status, err) {
        console.error(this.props.url, status, err.toString());
      }.bind(this)
    });
  },
  componentDidMount: function componentDidMount() {
    this.loadState();
    setInterval(this.loadState, 20000);
  },
  render: function render() {
    return React.createElement("tr", null, React.createElement("td", null, React.createElement("i", { className: "fa fa-leaf" }), " ", this.props.name), React.createElement("td", null, " ", this.state.state));
  }
});
