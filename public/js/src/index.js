import React from "react";
import ReactDOM from "react-dom";

class App extends React.Component {
	constructor(props){
		this.state ={
			siteurl: myplugin_ajax.siteurl,
			posts: null
		}
	}

	componentDidMount() {
		fetch(this.state.siteurl + '/wp-json/wp/v2/posts')
        .then(res => res.json())
        .then(
            (result) => {
                this.setState({
                    posts: result
                })
            }
        )
	}

	render() {
    	return ( <div></div>)
    }
}

ReactDOM.render(<App />, document.getElementById("myplugin"));