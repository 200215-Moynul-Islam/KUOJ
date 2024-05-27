#include<bits/stdc++.h>
using namespace std;

int main(){
    FILE* ftr=freopen("input.txt","r",stdin);
    int n,k;
    fscanf(ftr,"%d%d",&n,&k);
    int a[n];
    for(int i=0;i<n;i++){
        fscanf(ftr,"%d",&a[i]);
    }
    sort(a,a+n,greater<int>());
    int abc=a[k-1];
    int i;
    for(i=0;i<n;i++){
        if(a[i]<abc||a[i]==0){
            break;
        }
    }
    FILE* tptr=freopen("ans.txt","r",stdin);
    int si;
    int ck=1;
    int x=fscanf(tptr,"%d",&si);
    if(x==EOF){
        ck=0;
    }
    if(si!=i){
        ck=0;
    }
    if(ck==1){
        int x=fscanf(tptr,"%s",si);
        if(x!=EOF){
            ck=2;
        }
    }
    if(ck==1){
        cout<<"ACCEPTED";
    }
    else if(ck==0){
        cout<<"WRONG ANSWER";
    }
    else{
        cout<<"EXTRA LINES IN OUTPUT";
    }
    return 0;
}
