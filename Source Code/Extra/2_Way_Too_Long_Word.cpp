#include<bits/stdc++.h>
using namespace std;

int main(){
    freopen("input.txt","r",stdin);
    int n;
    cin>>n;
    string s[n];
    char ans[n][20];
    for(int i=0;i<n;i++){
        cin>>s[i];
        int l=s[i].size();
        if(l>10){
            int j=0;
            ans[i][0]=s[i][j];
            j++;
            l-=2;
            if(l/10>=1){
                ans[i][j]=l/10+48;
                j++;
            }
            ans[i][j]=l%10+48;
            j++;
            ans[i][j]=s[i][l+1];
            j++;
            ans[i][j]=0;
        }else{
            for(int j=0;j<=l;j++){
                ans[i][j]=s[i][j];
            }
        }
    }
    for(int i=0;i<n;i++){
        cout<<ans[i]<<endl;
    }
    return 0;
}


