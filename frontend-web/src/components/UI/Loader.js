import React from 'react'

import './../../assets/css/UI.css'


const Loader = props => {
    return (
        <div className="UI__loader">
            <div className="UI__loader_body">
                <p className="UI__loader_title">Загрузка</p>
                <div className="UI__loader_line"></div>
            </div>
        </div>
    )
}


export default Loader