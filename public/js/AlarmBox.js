"use strict";

var AlarmBox = React.createClass({
  displayName: "AlarmBox",

  getInitialState: function getInitialState() {
    return { etat: "", color:"", actual: "", action: "", link: ""};
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
          this.setState({ etat: false, color:"green lighten-2", colorSave: "green lighten-2", actual: "Désactivée", action: "Activer", link: "Désactivée"});
        } else if (data['state'] == '1') {
          this.setState({ etat: true, color:"red lighten-2", colorSave: "red lighten-2", actual: "Activée", action: "Désactiver", link: "Activée"});
        }
      }.bind(this),
      error: function (xhr, status, err) {
        console.error(this.props.url, status, err.toString());
        this.setState({ etat: false, color:"red darken-3", colorSave: "red darken-3", actual: "Erreur API", action: err.toString(), link: "Erreur API"});
      }.bind(this)
    });
  },
  setUp: function setUp() {
    $.ajax({
        method: "POST",
        url: "/api/v1/alarm/up/" + this.props.id,
        headers: {
          "Token-Id": this.props.tokenID,
          "Token-Key": this.props.tokenKey
        },
        success: function (data) {
           Materialize.toast('Succ  s', 4000);
        }.bind(this),
        error: function (xhr, status, err) {
         console.error(this.props.url, status, err.toString());
         Materialize.toast('An error occured', 4000);
        }.bind(this),
      });
  },
  handleClick: function handleClick() {
    if (this.state.etat == false) {
       setTimeout(this.setUp, 10000);
    }
    else {
      this.setUp();
    }
   },
  componentDidMount: function componentDidMount() {
    this.loadState();
    setInterval(this.loadState, 10000);
  },
  MouseOver: function MouseOver(){
    this.setState({link: this.state.action, color: "orange lighten-2"});
  },
  MouseOut: function MouseOut(){
    this.setState({link: this.state.actual, color: this.state.colorSave});
  },
  render: function render() {
    return React.createElement(
        "a",
        { "className" : "waves-effect waves-light btn "+ this.state.color, onClick: this.handleClick, onMouseOver: this.MouseOver, onMouseOut: this.MouseOut},
        this.state.link
      );
  }
});
