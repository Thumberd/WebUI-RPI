"use strict";

var Event = React.createClass({
  displayName: "Event",

  getInitialState: function getInitialState() {
    return { events: [],};
  },
  getEvents: function loadState() {
    $.get({
      url: "/api/v1/events",
      data: {},
      headers: {
        "Token-Id": this.props.tokenID,
        "Token-Key": this.props.tokenKey
      },
      success: function (data) {
        this.setState({events: JSON.parse(data)});
	console.log(this.state.events[0]);
      }.bind(this),
      error: function (xhr, status, err) {
        console.error(this.props.url, status, err.toString());
      }.bind(this)
    });
  },
  componentDidMount: function componentDidMount() {
    this.getEvents();
    setInterval(this.getEvents, 10000);
  },
  readAndUpdate: function readAndUpdate(){
    this.read();
    this.getEvents();
  },
  read: function read(){
    $.post({
      url: "/api/v1/event/" + this.state.events[0]['id'] + "/read",
      data: {},
      headers: {
        "Token-Id": this.props.tokenID,
        "Token-Key": this.props.tokenKey
      },
      success: function (data) {
        console.log(data);
      }.bind(this),
      error: function (xhr, status, err) {
        console.error(this.props.url, status, err.toString());
      }.bind(this)
    });
  },
  render: function render() {
	if(typeof this.state.events[0] != "undefined") {
	    return React.createElement("div", { "className": "card teal" }, React.createElement("div", { "className": "card-content white-text" }, React.createElement("span", { "className": "card-title" }, this.state.events[0]['title']), React.createElement("p", null, this.state.events[0]['created_at'] + " [" + this.state.events[0]['fired_by'] + "]: " + this.state.events[0]['message']),  React.createElement("a", { onClick: this.readAndUpdate, 'className': 'waves-effect waves-light btn grey'}, "Lu !")));
	} else {
	    return React.createElement("div", { "className": "card teal" }, React.createElement("div", { "className": "card-content white-text" }, React.createElement("span", { "className": "card-title" }, "Panel"), React.createElement('p', null, "Infos generales")));
	}
  }
});
