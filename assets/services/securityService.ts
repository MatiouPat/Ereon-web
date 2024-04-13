export class SecurityService
{
    private token: string | null;

    constructor() {
        // Récupérer le token JWT depuis le stockage local lors de l'instanciation de la classe
        this.token = localStorage.getItem('token');
    }

    public getHeaders(isPatchMethod: boolean = false, isMultipartMethod: boolean = false): Record<string, string> {
        // Créer les en-têtes de la requête avec le token JWT
        const headers: Record<string, string> = {};

        if(this.token) {
            headers['Authorization'] = `Bearer ${this.token}`;
        }

        return headers;
    }
}