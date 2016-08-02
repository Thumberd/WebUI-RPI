"use strict";

var GarageBox = React.createClass({
  displayName: "GarageBox",

  getInitialState: function getInitialState() {
    return { state: "", button: ""};
  },
  loadState: function loadState() {
    $.get({
      url: "/api/v2/garage/" + this.props.id,
      headers: {
        'Token-Id': this.props.tokenID,
        'Token-Key': this.props.tokenKey
      },
      success: function (data) {
        var stateG = JSON.parse(data)['state'];
        this.setState({ state: stateG });
        if (stateG == 1){
          this.setState({ button: "Fermer"});
        }
        else {
          this.setState({button: "Ouvrir"});
        }
      }.bind(this),
      error: function (xhr, status, err) {
        console.error(this.props.url, status, err.toString());
      }.bind(this)
    });
  },
  handleClick: function handleClick() {
    $.post({
      url: "/api/v2/garage/up/" + this.props.id,
      headers: {
        'Token-Id': this.props.tokenID,
        'Token-Key': this.props.tokenKey
      },
      success: function (data) {
        window.location.replace("code");
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
    return React.createElement("div", null,
        React.createElement("p", null, React.createElement("i", { className: "fa fa-leaf" }), " ", this.props.name),
        React.createElement("p", null, " ", this.state.state),
        React.createElement("p", null, React.createElement("a", { className: "waves-effect waves-light btn teal", onClick: this.handleClick}, "Ouvrir"))
    );
  }
});
