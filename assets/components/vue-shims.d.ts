// vue-shims.d.ts
declare module '*.vue' {
    import { Component } from 'vue';
    const component: Component;
    export default component;
}

declare module '*.frag' {
    const value: any;
    export default value;
}

declare module '*.vert' {
    const value: any;
    export default value;
}

declare module '*.png' {
    const value: any;
    export default value;
}