ReactDOM.render(React.createElement('h1',null,'Hello, world!'),document.getElementById('root'));var ToggleText=React.createClass({getInitialState:function(){return{showDefault:!0}},toggle:function(a){a.preventDefault(),this.setState({showDefault:!this.state.showDefault})},render:function(){var a=this.props.default;return this.state.showDefault||(a=this.props.alt),React.createElement("div",null,React.createElement("h1",null,"Hello ",a,"!"),React.createElement("a",{href:"",onClick:this.toggle},"Toggle"))}});ReactDOM.render(React.createElement(ToggleText,{"default":"World",alt:"Mars"}),document.getElementById("root"));