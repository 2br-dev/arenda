import React from 'react'
import { View, Text } from 'react-native'


const styles = {
    box: {
        backgroundColor: '#1976d2',
        height: '100%',
        justifyContent: 'center',
        alignItems: 'center'
    },
    text: {
        fontSize: 30,
        fontWeight: 'bold',
        color: '#fff'
    }
}

const Loader = props => {
    return (
        <View style={styles.box}>
            <Text style={styles.text}>Загрузка...</Text>
        </View>
    )
}


export default Loader