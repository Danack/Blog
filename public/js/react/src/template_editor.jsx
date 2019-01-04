function handleErrors(response) {
    if (!response.ok) {
        throw Error(response.statusText);
    }
    return response;
}



class BlogTextArea extends React.Component {
    constructor(props) {

//        props.content = "foobar";

        super(props);
        this.state = {
            value: '',
            timeoutID: undefined,
            countdown: 0,
            text: "output goes here",
        };

        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    componentDidMount() {
        // this.timerID = setInterval(
        //     () => this.tick(),
        //     4000
        // );
    }

    componentWillUnmount() {
        clearInterval(this.timerID);
    }

    updateOuput(text) {
        this.setState({
            timeoutID: undefined
        });

        let data = new FormData();
        let payload = {text: text};
        data.append( "json", JSON.stringify( payload ) );

        let request = new Request('/api/template_render', {
            method: 'POST',
            body: data
            //mode: 'cors',
            // redirect: 'follow',
            // headers: new Headers({
            //     'Content-Type': 'text/plain'
            // })
        });

        fetch(request)
            .then(handleErrors)
            // Convert to JSON
            .then((response) => response.json())
            .then((data) => {
                 this.setState(data)
            }
            )
            .catch((err) => {
                // if (err instanceof FetchError) {
                //     console.error(err.message)
                // }
                alert('error:' + err);
            });
    }

    handleTemplateResponse(data) {
        this.setState(data);
    }

    handleChange(event) {
        this.setState({value: event.target.value});
    }

    handleSubmit(event) {
        alert('A name was submitted: ' + this.state.value);
        event.preventDefault();
    }


    handleInput(e) {
        if (this.state.timeoutID !== undefined) {
            clearTimeout(this.state.timeoutID);
        }
        let text = e.target.value;
        let newTimeoutID = setTimeout(() => this.updateOuput(text), 2000);
        this.setState({
            timeoutID: newTimeoutID
        });
    }

    render() {
        return (
            <div>
            <textarea value={this.state.value} onInput={(e) => this.handleInput(e)} placeholder="Edit your text" rows="30" cols="80" onChange={this.handleChange}>
            </textarea>
            <div>
                <span dangerouslySetInnerHTML={{__html: this.state.text}} />
            </div>
            </div>
        );
    }
}

class TemplateEditor extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            text: "text",
        };
    }

    componentDidMount() {
        this.timerID = setInterval(
            () => this.tick(),
            4000
        );
    }

    componentWillUnmount() {
        //clearInterval(this.timerID);
    }
    tick() {
        fetch('/reactapi').then((response) => {
            let data = response.json();
            data.then((data) => {
                this.setState({
                    text: data["text"]
                });
            })
        }).catch((err) => {
            alert('error');
        });
    }

    render() {
        return (
            <div>
                <h1>This is an API thingy</h1>
                <p>And this is some '{this.state.text}'</p>
                <BlogTextArea />
            </div>
        );
    }
}

ReactDOM.render(
    <TemplateEditor content="Is this right?" />,
    document.getElementById('template_editor')
);