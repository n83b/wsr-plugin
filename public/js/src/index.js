import React from "react";
import ReactDOM from "react-dom";

class App extends React.Component {
	constructor(props){
		super(props);
		this.state ={
			siteurl: myplugin_ajax.siteurl,
			posts: null,
            isLoaded: false
		}
	}

	componentDidMount() {
		fetch(this.state.siteurl + '/wp-json/wp/v2/posts')
        .then(res => res.json())
        .then(
            (result) => {
                this.setState({
                    posts: result,
                    isLoaded: true
                })
            }
        )
	}

	render() {

        var isLoaded = this.state.isLoaded;
        var posts = this.state.posts;

        if (!isLoaded) {
          return <div>Loading...</div>;
        } else {    
        	return ( 
        		<div>
        			<ul>
        				{posts.map(post => (
        					<li key={post.id}>
        					{post.title.rendered}
        					</li>
        				))}
        			</ul>
        		</div>
        	)
        }
    }
}

ReactDOM.render(<App />, document.getElementById("myplugin"));