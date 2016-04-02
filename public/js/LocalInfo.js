var LocalInfo = React.createClass({
  displayName: "LocalInfo",

  getInitialState: function () {
    return { color: "orange", info: "" };
  },
  loadInfo: function () {
    $.get({
      url: "/localinfo",
      data: { _token: this.props.token },
      success: function (data) {
        if (data.indexOf("false") > -1) {
          return;
        }
        var res = JSON.parse(data);
        for (var k in res) {
          this.setState({ color: res[k].split('*')[0], info: res[k].split('*')[1] });
        }
      }.bind(this),
      error: function (xhr, status, err) {
        console.error(this.props.url, status, err.toString());
      }.bind(this)
    });
  },
  componentDidMount: function () {
    this.loadInfo();
    setInterval(this.loadInfo, 5000);
  },
  render: function () {
    return React.createElement("div", { "className": "col s12 m6 l12" }, React.createElement("div", { "className": "card " + this.state.color + " lighten-3" }, React.createElement("div", { "className": "card-content white-text" }, React.createElement("span", { "className": "card-title" }, "Info"), React.createElement("p", null, this.state.info))));
  }
});