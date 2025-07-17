export type Item = {
    id: number;
    name: string;
    memo?: string;
    user_id: number;
    created_at: string;
    updated_at: string;
    pivot?: {
        quantity: number;
        is_checked: boolean;
        created_at: string;
        updated_at: string;
    };
};

export type ShoppingList = {
    id: number;
    name: string;
    user_id: number;
    created_at: string;
    updated_at: string;
    items: Item[];
};
