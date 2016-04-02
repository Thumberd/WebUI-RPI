"use strict";

var AlarmBox = React.createClass({
  displayName: "AlarmBox",

  getInitialState: function getInitialState() {
    return { etat: "", color: ""};
  },
  loadState: function loadState() {
    $.post({
      url: "/api/v1/alarms/" + this.props.id,
      headers: {
        "Token-Id": this.props.tokenID,
        "Token-Key": this.props.tokenKey
      },
      success: function (data) {
        if (data['state'] == "0") {
          this.setState({ etat: false, color: "green lighten-1"});
          // $('#check' + this.props.id).prop('checked', false);
        } else if (data['state'] == '1') {
          this.setState({ etat: true, color: "red lighten-1"});
          // $('#check' + this.props.id).prop('checked', true);
        }
      }.bind(this),
      error: function (xhr, status, err) {
        console.error(this.props.url, status, err.toString());
      }.bind(this)
    });
  },
  handleClick: function handleClick() {
    this.setState({etat: !this.state.etat});
    console.log('tes');
    // $.post({
    //   url: "/api/v1/alarm/up/" + this.props.id,
    //   headers: {
    //     "Token-Id": this.props.tokenID,
    //     "Token-Key": this.props.tokenKey
    //   },
    //   success: function (data) {
    //     console.log(data);
    //     Materialize.toast('Request in progress', 2000);
    //   }.bind(this),
    //   error: function (xhr, status, err) {
    //     console.error(this.props.url, status, err.toString());
    //     Materialize.toast('An error occured', 4000);
    //   }.bind(this)
    // });
  },
  componentDidMount: function componentDidMount() {
    this.loadState();
    setInterval(this.loadState, 10000);
  },
  render: function render() {
    return React.createElement(
      "div",
      null,
      React.createElement(
        "td",
        null,
        this.props.name
      ),
      React.createElement(
        "td",
        null,
        React.createElement(
          "div",
          { "className": "switch" },
          React.createElement(
            "label",
            null,
            "Off",
            React.createElement("input", { type: "checkbox", id: "check" + this.props.id, checked: this.state.etat, onChange: this.handleClick }),
            React.createElement("span", { "className": "lever" }),
            "On"
          )
        )
      )
    );
  }
});
