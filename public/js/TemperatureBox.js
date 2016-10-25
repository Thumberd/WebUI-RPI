"use strict";

var TemperatureBox = React.createClass({
  displayName: "TemperatureBox",

  getInitialState: function getInitialState() {
    return { temp: "", bg : "inherit"};
  },
  loadTemp: function loadTemp() {
    $.get({
      url: "/api/v2/temperature/" + this.props.id,
      headers: {
        'Token-Id': this.props.tokenID,
        'Token-Key': this.props.tokenKey
      },
      success: function (data) {
        this.setState({ temp: data['value'] });
        var date = new Date(data['created_at'].replace(/-/g,'/'));
        var aDate = new Date();
	console.log(Math.abs(aDate-date));
        if (aDate - date > 1000 * 60 * 15){
          this.setState({bg: "red"});
        }
        else {
          this.setState({bg: "inherit"});
        }
      }.bind(this),
      error: function (xhr, status, err) {
        console.error(this.props.url, status, err.toString());
        this.setState({bg: "red"});
        this.setState({temp: "Error"});
      }.bind(this)
    });
  },
  componentDidMount: function componentDidMount() {
    this.loadTemp();
    setInterval(this.loadTemp, 300000);
  },
  render: function render() {
    return React.createElement("p", { "className" : this.state.bg}, " ", this.state.temp);
  }
});
