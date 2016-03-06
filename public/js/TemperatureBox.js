"use strict";

var TemperatureBox = React.createClass({
  displayName: "TemperatureBox",

  getInitialState: function getInitialState() {
    return { temp: "" };
  },
  loadTemp: function loadTemp() {
    $.post({
      url: "/api/v1/temperature",
      data: { _token: this.props.token, id: this.props.id },
      success: function (data) {
        this.setState({temp: data['value']});
      }.bind(this),
      error: function (xhr, status, err) {
        console.error(this.props.url, status, err.toString());
      }.bind(this)
    });
  },
  componentDidMount: function componentDidMount() {
    this.loadTemp();
    setInterval(this.loadTemp, 300000);
  },
  render: function render() {
    return React.createElement(
      "tr",
      null,
      React.createElement(
        "td",
        null,
        React.createElement("i", { className: "fa fa-leaf" }),
        " ",
        this.props.name
      ),
      React.createElement(
        "td",
        null,
        " ",
        this.state.temp
      )
    );
  }
});
