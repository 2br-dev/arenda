
export function resizer() {
    window.onload = () => {
        
        const resizer = () => {
            const APP = document.querySelector('.app')
            const NODES = APP.querySelectorAll('*')
            if(window.innerWidth <= 1000) {
                NODES.forEach(node => {
                    node.classList.add('mobile')
                })
            } else {
                NODES.forEach(node => {
                    node.classList.remove('mobile')
                })
            }
        }
        
        window.addEventListener("resize", resizer)
        resizer()
    }
}