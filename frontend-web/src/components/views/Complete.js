import React, { useState } from 'react'

import './../../assets/css/Complete.css'


const Complete = ({isVisible, text}) => {
 
    const classes = isVisibleClass(isVisible)    

    return (
        <div className={classes}>
            <p className="complete__text">{text}</p>
        </div>
    )
}


export default Complete


const isVisibleClass = flag => {
    let temp = 'complete__overlay'
    if(flag) {
        temp += ' complete__overlay-visible'
    }

    return temp
}