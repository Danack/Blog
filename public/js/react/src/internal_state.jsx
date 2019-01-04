/*
 Managing internal state
 Sets the intial state of the component and then,
 when a link is clicked, updates the state.
 When the state updates, the component intelligently
 and efficiently re-renders.
 Note that the `onClick` is the same as the JavaScript
 `onClick` event handler. There are many common browser
 events that are supported by React. See them all at:
 http://facebook.github.io/react/docs/events.html
 */


class ToggleText extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            showDefault: true
        };
    }

    toggle(e) {
        // Prevent following the link.
        e.preventDefault();

        // Invert the chosen default.
        // This will trigger an intelligent re-render of the component.
        this.setState({ showDefault: !this.state.showDefault })
    }

    render() {
        // Default to the default message.
        let message = this.props.default;

        // If toggled, show the alternate message.
        if (!this.state.showDefault) {
            message = this.props.alt;
        }

        return (
          <div>
            <h1>Hello {message}!</h1>
            <a href="" onClick={(e) => this.toggle(e)}>Toggle</a>
          </div>
        );
    }
}

ReactDOM.render(
    <ToggleText default="World" alt="Mars" />,
    document.getElementById('toggle_text')
);