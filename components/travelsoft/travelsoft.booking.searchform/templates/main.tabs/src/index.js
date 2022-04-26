import React from "react";
import ReactDom from 'react-dom';
import { render } from "react-dom";
const App = () =>{
    return (
        <div>
        <h1>Hello Parcel</h1>
    </div>
)
};
render(<App />, document.getElementById("app"));