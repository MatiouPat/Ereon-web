import axios from "axios";
import { SecurityService } from "../services/securityService";

export class QueryBuilder
{
    
    private securityService: SecurityService = new SecurityService();

    private method: string;

    private url: string;

    private data: any;

    private isMultipartMethod: boolean = false;

    constructor(method: string, url: string)
    {
        this.method = method;
        this.url = url;
    }

    public addData(data: any): QueryBuilder
    {
        this.data = data;

        return this;
    }

    public setIsMultipartMethod(isMultipartMethod: boolean): QueryBuilder
    {
        this.isMultipartMethod = isMultipartMethod;

        return this;
    }

    public getResult(): Promise<any[]>
    {
        return axios({
            method: this.method,
            url: this.url,
            headers: this.getHeaders(),
            data: this.data
        })
        .then(res => {
            return res.data['hydra:member']
        })
    }

    public getOneOrNullResult(): Promise<any>
    {
        return axios({
            method: this.method,
            url: this.url,
            headers: this.getHeaders(),
            data: this.data
        })
        .then((res) => {
            return res.data
        })
    }

    private getHeaders(): Record<string, string>
    {
        let headers = this.securityService.getHeaders();

        if(this.isMultipartMethod) {
            headers['Content-Type'] = 'multipart/form-data';
        }else {
            if(this.method === 'PATCH') {
                headers['Content-Type'] = 'application/merge-patch+json';
            }else {
                headers['Content-Type'] = 'application/json';
            }
        }

        return headers;

    }

}