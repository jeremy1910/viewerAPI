import React, {Component} from 'react';
import ReactDom from 'react-dom';




class Index extends Component{

    render(){
        return(
            <div>
                <h1>mon Titre</h1>
            </div>
        )
    }
}

ReactDom.render(<Index />, document.getElementById('index'))